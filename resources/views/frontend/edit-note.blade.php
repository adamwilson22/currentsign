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
                            <div class="d-flex justify-content-between">
                                <h4>Edit Notes</h4>
                                <a href="{{ url('user/notes') }}" class="btn btn-primary">View All</a>
                            </div>
                             
                               <hr/>
                           
        
                         <div class="card">
    <div class="card-header">Edit note</div>
    <div class="card-body">
      <form action="{{ url('user/update-note/'.$note->id) }}" method="POST" >
          @csrf
        
          <div class="form-group mb-3">
            <label for="noteDate">Title</label>
            <input type="text" class="form-control" name="title" value="{{ $note->title }}" id="noteDate" required>
          </div>
          <div class="form-group mb-3">
            <label for="noteDesc">Description</label>
            <textarea class="form-control" id="noteDesc" value="{{ $note->description }}" name="description" placeholder="Enter note description" required>{{ $note->description }}</textarea>
          </div>
        
        <button type="submit" class="btn btn-primary">update Note</button>
      </form>
    </div>
  </div>
        
        
        
                        </div>
                    </div>
                  
                </div>
            </div>
        </section>
       
        @include('frontend.include.footer')
</body>



</html>