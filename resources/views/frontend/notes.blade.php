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
                                <h4>Notes</h4>
                                <a href="{{ url('user/add-note') }}" class="btn btn-primary">Add Note</a>
                            </div>
                             
                               <hr/>
                           
        
                         <div class="card">
    <div class="card-header">All Notes</div>
    <div class="card-body p-0">
      <table class="table table-bordered table-striped mb-0">
        <thead class="thead-dark">
          <tr>
            <th>#</th>
            <th>Date</th>
            <th>Description</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody id="notesTableBody">
            @foreach($notes as $note)
          <tr>
            <td>1</td>
            <td>{{ $note->created_at }}</td>
            <td>{{ $note->title }}</td>
            <td class="action-btns">
              <a href="{{ url('user/edit-note/'.$note->id) }}" class="btn btn-sm btn-warning">Edit</a>
              <a href="{{ url('user/delete-note/'.$note->id) }}" class="btn btn-sm btn-danger">Delete</a>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
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