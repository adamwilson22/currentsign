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
                        @if(session('signing_link'))
                            <div class="mt-2">
                                <a href="{{ session('signing_link') }}" target="_blank" rel="noopener">Open signing link</a>
                            </div>
                        @endif
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
                                <strong>PDF{{ $pdf->id }}</strong>
                                <p>Signed file ready to preview or edit.</p>
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

<div class="modal fade cs-theme-modal" id="upload" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content cs-theme-modal__content">
      
        <!-- Modal Header -->
        <div class="modal-header cs-theme-modal__header">
          <h4 class="modal-title">Send Document</h4>
          <button type="button" class="close cs-theme-modal__close" data-dismiss="modal" aria-label="Close">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body cs-theme-modal__body">
          <form action="{{ url('/user/submitsignacture') }}" method="POST" enctype="multipart/form-data" class="cs-app-form">
              @csrf
              <div class="form-group cs-field">
                  <label>Add Document</label>
                  <input type="file" name="pdf_path" class="form-control" required>
              </div>
              <div class="form-group cs-field">
                  <label>Email</label>
                  <input type="email" class="form-control" name="email" > 
              </div>
              <div class="form-group cs-field">
                  <label>Page no. to sing</label>
                  <input type="number" class="form-control" value="1" name="page" > 
              </div>
              <div class="form-group mb-0">
                  <button type="submit" class="cs-btn cs-btn--primary">Submit</button>
              </div>
          </form>
        </div>
        
        
        
      </div>
    </div>
  </div>

@include('frontend.include.footer')
</body>


</html>