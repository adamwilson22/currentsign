<?php 
   $seo['short_title']       = "Car No Of Seats"; 
   $seo['full_title']        = "Car No Of Seats"; 
   $seo['short_description'] = "Car No Of Seats"; 
   $seo['full_description']  = "Car No Of Seats"; 
   $seo['keywords']          = "Car No Of Seats"; 
   
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
               <h3 style="display: inline;">Car Number Of Seats</h3>
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
               <iframe id="myIframe" src="<? echo url(''); ?>/xcrud/admin/cars/car_no_of_seats.php?search=<?php echo @$_REQUEST['search']; ?>"></iframe>
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

            </div>
         </div>
      </div>
   </div>
</div>
@include('admin.include.footer')