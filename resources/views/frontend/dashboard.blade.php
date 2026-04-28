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
                    <div class="alert alert-success">{{ session('success') }}</div>
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
                        <h3>Signed PDFs</h3>
                    </div>
                    @forelse($signeds as $pdf)
                        <div class="cs-app-list-item">
                            <div>
                                <strong>PDF{{ $pdf->id }}</strong>
                                <p>Signed file ready to preview or edit.</p>
                            </div>
                            <div class="cs-app-list-item__actions">
                                <a href="{{ url('/public/'.$pdf->signature) }}" target="_blank" class="cs-btn cs-btn--ghost-white">View</a>
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

<div class="modal" id="upload">
    <div class="modal-dialog">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <a  href="{{ url('user/documents') }}" class="modal-header">
          <h4 class="modal-title">Send Document</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </a>
        
        <!-- Modal body -->
        <div class="modal-body">
          <form action="{{ url('/user/submitsignacture') }}" method="POST" enctype="multipart/form-data">
              @csrf
              <div class="form-group">
                  <label>Add Document</label>
                  <input type="file" name="pdf_path" class="form-control" style="height: 45px;"  required>
              </div>
              <div class="form-group">
                  <label>Email</label>
                  <input type="email" class="form-control" name="email" > 
              </div>
              <div class="form-group">
                  <label>Page no. to sing</label>
                  <input type="number" class="form-control" value="1" name="page" > 
              </div>
              <div class="form-group">
                  <input type="submit" class="btn btn-primary" value="Submit">
              </div>
          </form>
        </div>
        
        
        
      </div>
    </div>
  </div>

@include('frontend.include.footer')
</body>


</html>