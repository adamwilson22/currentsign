@include('frontend.include.header')
       
       
        
       
        <section class="doc_documentation_area ptb-100">
            <div class="overlay_bg"></div>
            <div class="container custom_container">
                <div class="row">
                    <div class="col-lg-3 doc_mobile_menu display_none">
                           @include('frontend.include.aside')
                    </div>
                    <div class="col-lg-9 col-md-9">
                        <div class="documentation_info">
                             <h4>Dashboard</h4>
                             @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

                               <hr/>
                            <div class="status-box">
            <div class="status-card">
                <p>Total Docs</p>
                <h2 class="text-warning">{{ $file_count  }}</h2>
            </div>
            <div class="status-card">
                <p>Total Notes</p>
                <h2 class="text-success">{{ $note_count  }}</h2>
            </div>
        </div>
        
        <h5 class="mt-4">Signature Status</h5>
        <ul class="list-group mb-3">
            <li class="list-group-item">Awaiting Signature <span class="badge bg-secondary float-end">{{ $awaiting }}</span></li>
            <li class="list-group-item">Signed <span class="badge bg-primary float-end">{{ $signed }}</span></li>
            <li class="list-group-item">Rejected <span class="badge bg-danger float-end">0</span></li>
        </ul>
        
        <h5>Quick Actions</h5>
        <div class="quick-actions">
            <a href="{{ url('user/documents') }}" class="btn btn-primary">Upload Document</a>
            <button class="btn btn-primary" data-toggle="modal" data-target="#upload">Send for Signature</button>
        </div>
        
        <h5 class="mt-4">PDF File <span class="float-end"></span></h5>
        @foreach($signeds as $pdf)
        <div class="pdf-file">
           
            <div class="pdf-card">
                <img src="https://cdn-icons-png.flaticon.com/512/337/337946.png" alt="Folder">
                <div class="info">
                     <a href="{{ url('/public/'.$pdf->signature) }}" target="_blank">
                    <strong>PDF{{ $pdf->id }}</strong>
                     </a>
                </div>
                <span class="icon"><a href="{{ url('/edit-pdf/'.$pdf->id) }}" >Edit pdf</a></span>
            </div>
           
        </div>  
      @endforeach
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