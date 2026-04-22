@include('frontend.include.header')
@include('frontend.include.nav-bar')


 <main class="main">

        <!-- breadcrumb -->
        <!--<div class="site-breadcrumb" style="background: url(assets/img/breadcrumb/01.jpg)">-->
        <!--    <div class="container">-->
        <!--        <h2 class="breadcrumb-title">Projects</h2>-->
        <!--        <ul class="breadcrumb-menu">-->
        <!--            <li><a href="index.html">Home</a></li>-->
        <!--            <li class="active">Projects</li>-->
        <!--        </ul>-->
        <!--    </div>-->
        <!--</div>-->
        <!-- breadcrumb end -->

        
        <!-- dashboard area -->
        <div class="dashboard-section pt-5 mb-110">
        <div class="container">
         <div class="row">
             
           <div class="profile-nav col-md-3">
         
      
        @include('frontend.include.user-sidebar')
  </div>
 
  <div class="profile-info col-md-9">
   
      <div>
          <div class="row">
              <div class="col-md-12 mb-2 d-flex" style="justify-content: space-between;">
                  <h4>Create Project</h4>
                  
                  <a href="{{ url('user/project-list') }}" class="theme-btn">View all</a>
              </div>
              <div class="col-md-12">
      <form action="{{ url('user/save-project') }}" method="POST"  enctype="multipart/form-data" >
          @csrf
        <div class="row">
            <div class="form-group col-lg-12 mb-3">
                <label>Project Name</label>
                <input type="text" name="project_name" class="form-control">
            </div>
            <div class="form-group col-lg-6 mb-3">
                <!--<label>Category</label>-->
                <!--<select class="form-control">-->
                <!--    <option>Select Category</option>-->
                <!--</select>-->
                 <label>Client </label>
                <input type="text" name="client" class="form-control">
            </div>
            <div class="form-group col-lg-6 mb-3">
                <label>Upload Picture</label>
                <input type="file" name="image" class="form-control">
            </div>
            <div class="form-group col-lg-12 mb-3">
                <label>Project Manager </label>
                <input type="text" name="project_manager" class="form-control">
            </div>
           <div class="form-group col-lg-12 mb-3">
                <label>Site Manager </label>
                <input type="text" name="site_manager" class="form-control">
            </div>
            <div class="form-group col-lg-12 mb-3">
                <label>Project Coordinator </label>
                <input type="text" name="project_coordinator" class="form-control">
            </div>
           <div class="form-group col-lg-12 mb-3">
                <label>Client Contact </label>
                <input type="text" name="client_contact" class="form-control">
            </div>
            
            <div class="form-group col-lg-12 mb-3">
                <label>Description</label>
				<textarea name="description" class="form-control"></textarea>
            </div>

            <div class="form-group col-lg-12">
               
                <input type="submit" class="btn btn-success"  value="Create Project"> 
            </div>

        </div>
      </form>

    </div>
            </div>
           </div>
          </div>  
              
         </div>
         <!--end row -->
     </div>
    </div>
        <!-- end dashboard area -->


        

    </main>
    
    
    
    <!-- footer area -->
@include('frontend.include.footer')
</body>


</html>