@include('frontend.include.header')
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">   
       
        
       
        <section class="doc_documentation_area ptb-100">
            <div class="overlay_bg"></div>
            <div class="container custom_container">
                <div class="row">
                    <div class="col-lg-3 doc_mobile_menu display_none">
                        @include('frontend.include.aside')
                    </div>
                    <div class="col-lg-9 col-md-9">
                        <div class="documentation_info">
                            <div class="d-flex justify-content-between">
                             <h4>Documents</h4>
                             <!--<a href="javascript:void()" data-toggle="modal" data-target="#adddoc" class="btn btn-primary text-white">Add document</a>-->
                             </div>
                               <hr/>
                           
        
                         <label class="upload-box mt-3" style="cursor: pointer; width:100%" data-toggle="modal" data-target="#upload">
                           
           <i class="fa fa-file" style="color: #556ffe;font-size: 40px;"></i>
            <p class="text-primary">Upload File</p>
        </label>
        
        <div class="d-flex justify-content-between mt-4">
            <h5>All Documents</h5>
            <!--<a href="#" class="text-primary">Select all</a>-->
        </div>
        @foreach($files as $file)
        <div class="doc-card">
            <i class="fa fa-file" style="color: #556ffe;font-size: 40px;"></i>
            <div class="info">
                <strong>{{ $file->file_name }}</strong>
                <p class="text-muted mb-0">{{ $file->created_at }}</p>
            </div>
            <div class="actions">
                <a href="{{ url('public/'.$file->file_path) }}" target="_blank"  class="edit"> <i class="fa fa-eye"></i> View</a>
                <a href="{{ url('user/delete-doc/'.$file->id) }}" 
   class="btn delete" 
   onclick="return confirm('Are you sure you want to delete this document?');">
   <i class="fa fa-trash"></i>
</a>

            </div>
        </div>
        @endforeach
        
        
        
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
        
        <div class="modal" id="adddoc">
    <div class="modal-dialog" style="max-width: 600px;">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Add document</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
           <form>
      <div class="form-group">
        <label for="docName">Document Name</label>
        <input type="text" class="form-control" id="docName" placeholder="e.g., Work Report March" required>
      </div>

      <div class="form-group">
        <label for="docCategory">Category</label>
        <select class="form-control" id="docCategory" required>
          <option value="">Select Category</option>
          <option>Work</option>
          <option>Personal</option>
          <option>Finance</option>
          <option>Legal</option>
        </select>
      </div>

      <div class="form-group">
        <label for="docFile">Upload File</label>
        <input type="file" class="form-control-file" id="docFile" required>
      </div>

      <button type="submit" class="btn btn-primary btn-block">Submit Document</button>
    </form>
        </div>
        
        
        
      </div>
    </div>
  </div>  
          @include('frontend.include.footer')
</body>


</html>