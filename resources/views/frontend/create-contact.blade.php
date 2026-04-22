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
                  <h4>Create Contact</h4>
                  
                  <a href="{{ url('user/contacts') }}" class="theme-btn">View all</a>
              </div>
              <div class="col-md-12">
      <form action="{{ url('user/save-contact') }}" method="POST"  enctype="multipart/form-data" >
          @csrf
        <div class="row">
            <div class="form-group col-lg-12 mb-3">
                <label>Company Name</label>
                <input type="text" name="company_name"  class="form-control">
            </div>
            <div class="form-group col-lg-12 mb-3">
                <label>Contact Name</label>
                <input type="text" name="contect_name" class="form-control">
            </div>
            <div class="form-group col-lg-12 mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control">
            </div>
            <div class="form-group col-lg-12 mb-3">
                <label>Phone Number</label>
                <input type="text" name="phone_number" class="form-control">
            </div>
            
            <div class="form-group col-lg-6 mb-3">
                <label>Address</label>
                <input type="text" name="address" class="form-control">
            </div>
            <div class="form-group col-lg-6 mb-3">
                <label>Status</label>
                <select class="form-control" name="contact_status" >
                    <option value="" >Select Status</option>
                    <option value="ACTIVE" >Active</option>
                    <option value="INACTIVE" >Inactive</option>
                </select>
            </div>

            <div class="form-group col-lg-12">
               
                <input type="submit" class="btn btn-success"  value="Save"> 
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

@include('frontend.include.footer')

</body>


</html>