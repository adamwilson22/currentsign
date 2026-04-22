<?php 
   $seo['short_title']       = "User"; 
   $seo['full_title']        = "User"; 
   $seo['short_description'] = "User"; 
   $seo['full_description']  = "User"; 
   $seo['keywords']          = "User"; 
   
   $users = array();
   ?>
@include('admin.include.header', ['seo' => $seo])
<?php  //show_error_or_success_helper($errors); ?>
<div class="row">
   <div class="col-12 grid-margin">
      <div class="card">
         <div class="card-body">
            <div class="pull-right" style="display: inline;">
               <!--<a class="btn btn-success" href="{{ route('users.create') }}"> Add User</a>-->
               <h3 style="display: inline;">Users</h3>
            </div>
            <div class="pull-right" style="float: right;">
               <div class="input-group">
                  <form style="display: inline;">
                     <div class="btn-group" role="group" aria-label="Basic example">
                        <input type="text" class="form-control" placeholder="Search" name="search" value="<?php echo @$_REQUEST['search']; ?>">
                        <button class="btn btn-primary" type="submit">Search</button>
                        <!--<input type="hidden" class="form-control" name="page" value="<?php echo @$_REQUEST['page']; ?>">-->
                     </div>
                  </form>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<style>
   svg.w-5.h-5 {
   width: 10px;
   }
</style>
<div class="row">
   <div class="col-lg-12 grid-margin stretch-card">
      <div class="card">
         <div class="card-body">
            <div class="table-responsive">
               <!---------------------------------->
               <!---------------------------------->
               <style>
                  iframe {
                  width: 100%;
                  min-height:300px;
                  border: none; /* Remove border for a cleaner look */
                  overflow-x: hidden;
                  }
               </style>
               <iframe id="myIframe" src="<? echo url(''); ?>/xcrud/admin/vehicles-models.php?search=<?php echo @$_REQUEST['search']; ?>"></iframe>
               <script>
                  function resizeIframe() {
                      var iframe = document.getElementById('myIframe');
                      var innerDoc = iframe.contentDocument || iframe.contentWindow.document;
                      iframe.style.height = innerDoc.body.scrollHeight + 'px';
                  }
                  
                  // Call the resizeIframe function when the content inside the iframe changes
                  document.getElementById('myIframe').onload = resizeIframe;
                  
                  // Also call it initially to set the correct height
                  resizeIframe();
                  
                  // Add an event listener to the iframe's content document to adjust the height on click inside iframe
                  document.getElementById('myIframe').addEventListener('load', function() {
                      var innerDoc = this.contentDocument || this.contentWindow.document;
                      innerDoc.addEventListener('click', resizeIframe);
                  });
                  
                  // Add an event listener to the parent document to adjust the height on click outside iframe
                  document.addEventListener('click', function(event) {
                      if (event.target !== document.getElementById('myIframe')) {
                          resizeIframe();
                      }
                  });
                  
                  setInterval(resizeIframe, 2000); // Call resizeIframe every 2 seconds
                  
               </script>
               <!---------------------------------->
               <!---------------------------------->
               <?php
                  /*
                  <table class="table table-striped">
                  <thead>
                    <tr>
                       <th>
                          User ID
                  
                       </th>
                       <th>
                         First and last name
                  
                       </th>
                       <th>
                          Email
                       </th>
                       <th>
                          Mobile
                       </th>
                       <th>
                          Action
                       </th>
                  
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($users as $user)
                    <tr>
                       <td class="py-1">{{ $user->id }}</td>
                       <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                       <td>{{ $user->email }}</td>
                       <td></td>
                       
                       <td>
                          <form action="{{ route('users.destroy',$user->id) }}" method="POST">
                             <div class="btn-group" role="group" aria-label="Basic example">
                                <a class="btn btn-primary" href="{{ route('users.edit',$user->id) }}">Edit</a>
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                             </div>
                          </form>
                       </td>
                  
                    </tr>
                    @endforeach
                  </tbody>
                  </table>
                  */
                  ?>
            </div>
         </div>
      </div>
   </div>
</div>
@include('admin.include.footer')