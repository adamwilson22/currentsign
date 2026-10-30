@include('frontend.include.header')
<section class="cs-app-page">
    <div class="container">
        <div class="cs-app-shell">
            @include('frontend.include.aside')
            <div class="cs-app-main">
                <div class="cs-app-head">
                    <h1>Dashboard</h1>
                    <p>Overview of documents, notes, and signature activity.</p>
                </div>

                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="cs-app-stats">
                    <article class="cs-app-stat">
                        <span>Total Docs</span>
                        <strong>{{ $file_count }}</strong>
                    </article>
                    <article class="cs-app-stat">
                        <span>Total Notes</span>
                        <strong>{{ $note_count }}</strong>
                    </article>
                    <article class="cs-app-stat">
                        <span>Awaiting</span>
                        <strong>{{ $awaiting }}</strong>
                    </article>
                    <article class="cs-app-stat">
                        <span>Signed</span>
                        <strong>{{ $signed }}</strong>
                    </article>
                </div>

                <div class="cs-app-card">
                    <div class="cs-app-card__head">
                        <h3>Quick Actions</h3>
                    </div>
                    <div class="cs-app-actions">
                        <a href="{{ url('user/documents') }}" class="cs-btn cs-btn--primary">Upload Document</a>
                        <button class="cs-btn cs-btn--ghost-white" data-toggle="modal" data-target="#upload">Send for Signature</button>
                        <a href="{{ url('/pricing') }}" class="cs-btn cs-btn--ghost-white">Plans &amp; billing</a>
                        @auth
                            @if(!empty(Auth::user()->stripe_customer_id))
                                <a href="{{ url('/billing/portal') }}" class="cs-btn cs-btn--ghost-white">Manage subscription</a>
                            @endif
                        @endauth
                    </div>
                </div>

                <div class="cs-app-card">
                    <div class="cs-app-card__head">
                        <h3>Awaiting Signature</h3>
                    </div>
                    @forelse($awaitings as $pdf)
                        @php
                            $awaitPath = ltrim((string) ($pdf->pdf_path ?? ''), '/');
                            if (str_starts_with($awaitPath, 'public/')) {
                                $awaitPath = substr($awaitPath, 7);
                            }
                        @endphp
                        <div class="cs-app-list-item">
                            <div>
                                <strong>Doc #{{ $pdf->id }}</strong>
                                <p>{{ $pdf->email ?: 'No recipient email' }} · {{ $pdf->status }}</p>
                            </div>
                            <div class="cs-app-list-item__actions">
                                @if($awaitPath)
                                    <a href="{{ asset($awaitPath) }}" target="_blank" class="cs-btn cs-btn--ghost-white">View PDF</a>
                                @endif
                                <a href="{{ url('/signature?id='.$pdf->id) }}" target="_blank" class="cs-btn cs-btn--primary">Open Sign Link</a>
                            </div>
                        </div>
                    @empty
                        <p class="cs-app-empty mb-0">No awaiting documents.</p>
                    @endforelse
                </div>

                <div class="cs-app-card">
                    <div class="cs-app-card__head">
                        <h3>Signed PDFs</h3>
                    </div>
                    @forelse($signeds as $pdf)
                        @php
                            $viewPath = ltrim((string) ($pdf->signature ?: $pdf->pdf_path), '/');
                            if (str_starts_with($viewPath, 'public/')) {
                                $viewPath = substr($viewPath, 7);
                            }
                        @endphp
                        <div class="cs-app-list-item">
                            <div>
                                <strong>Doc #{{ $pdf->id }}</strong>
                                <p>{{ $pdf->email ?: 'Recipient' }} · Signed{{ $pdf->updated_at ? ' · ' . \Carbon\Carbon::parse($pdf->updated_at)->diffForHumans() : '' }}</p>
                            </div>
                            <div class="cs-app-list-item__actions">
                                @if($viewPath)
                                    <a href="{{ asset($viewPath) }}" target="_blank" class="cs-btn cs-btn--ghost-white">View</a>
                                @endif
                                <a href="{{ url('/edit-pdf/'.$pdf->id) }}" class="cs-btn cs-btn--primary">Edit PDF</a>
                            </div>
                        </div>
                    @empty
                        <p class="cs-app-empty mb-0">No signed PDFs yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</section>

@php
    $pendingSend = session(\App\Services\PendingSignatureSend::SESSION_KEY);
    $hasPendingSend = is_array($pendingSend)
        && !empty($pendingSend['pdf_path'])
        && (int) ($pendingSend['user_id'] ?? 0) === (int) auth()->id();
    $reopenSend = (bool) session('reopen_send_modal');
@endphp
<div class="modal fade cs-theme-modal" id="upload" tabindex="-1" role="dialog" aria-hidden="true"
     data-auto-open="{{ $reopenSend ? '1' : '0' }}">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content cs-theme-modal__content">
      
        <!-- Modal Header -->
        <div class="modal-header cs-theme-modal__header">
          <h4 class="modal-title">Send Document</h4>
          <button type="button" class="close cs-theme-modal__close" data-dismiss="modal" aria-label="Close">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body cs-theme-modal__body" style="color:#f3f6fb;">
          <form action="{{ url('/user/submitsignacture') }}" method="POST" enctype="multipart/form-data" class="cs-app-form">
              @csrf
              @if($hasPendingSend)
                  <input type="hidden" name="use_pending" value="1">
                  <div class="form-group cs-field">
                      <label style="color:rgba(255,255,255,.85);">Document</label>
                      <p class="mb-0" style="color:#fff;font-weight:600;">
                          {{ $pendingSend['original_name'] ?? basename($pendingSend['pdf_path']) }}
                      </p>
                      <small style="color:rgba(255,255,255,.65);">Saved — no need to choose the file again.</small>
                  </div>
              @else
                  <div class="form-group cs-field">
                      <label style="color:rgba(255,255,255,.85);">Add Document</label>
                      <input type="file" name="pdf_path" class="form-control" required accept="application/pdf">
                  </div>
              @endif
              <div class="form-group cs-field">
                  <label style="color:rgba(255,255,255,.85);">Email</label>
                  <input type="email" class="form-control" name="email" value="{{ old('email', $hasPendingSend ? ($pendingSend['email'] ?? '') : '') }}" style="color:#fff;">
              </div>
              <div class="form-group cs-field">
                  <label style="color:rgba(255,255,255,.85);">Page no. to sign</label>
                  <input type="number" class="form-control" name="page" value="{{ old('page', $hasPendingSend ? ($pendingSend['page'] ?? 1) : 1) }}" style="color:#fff;">
              </div>
              <div class="form-group mb-0">
                  <button type="submit" class="cs-btn cs-btn--primary">Submit</button>
              </div>
          </form>
        </div>
        
        
        
      </div>
    </div>
  </div>
<script>
(function () {
    var modal = document.getElementById('upload');
    if (!modal || modal.getAttribute('data-auto-open') !== '1') return;
    function openSendModal() {
        if (window.jQuery && jQuery.fn && jQuery.fn.modal) {
            jQuery(modal).modal('show');
            return;
        }
        modal.classList.add('show');
        modal.style.display = 'block';
    }
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', openSendModal);
    } else {
        openSendModal();
    }
})();
</script>

@include('frontend.include.footer')
</body>


</html>