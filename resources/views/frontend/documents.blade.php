@include('frontend.include.header')
<section class="cs-app-page">
    <div class="container">
        <div class="cs-app-shell">
            @include('frontend.include.aside')
            <div class="cs-app-main">
                <div class="cs-app-head">
                    <h1>Documents</h1>
                    <p>Upload, view, and manage your files securely.</p>
                </div>

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

                <button type="button" class="cs-app-upload-trigger" data-toggle="modal" data-target="#upload">
                    <i class="fa fa-file" aria-hidden="true"></i>
                    <span>Upload File</span>
                </button>

                <div class="cs-app-card">
                    <div class="cs-app-card__head">
                        <h3>All Documents</h3>
                    </div>
                    @forelse($files as $file)
                        <div class="cs-app-list-item">
                            <div>
                                <strong>{{ $file->file_name ?: 'Untitled document' }}</strong>
                                <p>{{ $file->created_at }}</p>
                            </div>
                            <div class="cs-app-list-item__actions">
                                <a href="{{ url('public/'.$file->file_path) }}" target="_blank" class="cs-btn cs-btn--ghost-white">View</a>
                                <a href="{{ url('user/delete-doc/'.$file->id) }}" class="cs-btn cs-btn--primary" onclick="return confirm('Are you sure you want to delete this document?');">Delete</a>
                            </div>
                        </div>
                    @empty
                        <p class="cs-app-empty mb-0">No documents uploaded yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</section>
        
     <!-- The Modal -->
  <div class="modal" id="upload">
    <div class="modal-dialog">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <a  href="{{ url('user/documents') }}" class="modal-header">
          <h4 class="modal-title">Upload Document</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </a>
        
        <!-- Modal body -->
        <div class="modal-body">
          <form action="{{ url('/user/save-doc') }}" method="POST" enctype="multipart/form-data">
              @csrf
              <div class="form-group">
                  <label>Add Document</label>
                  <input type="file" name="file" class="form-control" style="height: 45px;">
              </div>
              <div class="form-group">
                  <label>File name</label>
                  <textarea class="form-control" name="file_name" ></textarea>
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