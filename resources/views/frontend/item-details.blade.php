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
                  <h4>Item details </h4>
                  <div>
                 
                  <a href="{{ url('user/item-list/'.$item->project_id) }} " class="theme-btn">View All</a>
                  </div>
              </div>
              
           </div>
           
           <div>
    
     <div class="row mb-2">
         <div class="col-lg-12 mb-2">
              <h5>Item Information</h5>
         </div>
    
     <div class="col-md-12 mb-2">
                  
                   <div class="card mb-3">
          <div class="card-body bio-graph-info">
             
              <div class="row">
                  <div class="bio-row">
                      <p><span> Item Number </span>: {{ $item->id }}</p>
                  </div>
                  <div class="bio-row">
                      <p><span> Item Name </span>: {{ $item->item_name }}</p>
                  </div>
                   @php
                   $project = DB::table('projects')->where('id', $item->project_id)->first();
                   @endphp
                  <div class="bio-row">
                      <p><span> Project Name </span>: {{ $project->project_name }}</p>
                  </div>
                  <div class="bio-row">
                      <p><span>Assigned To </span>: {{ $item->assign_to }}</p>
                  </div>
                  <div class="bio-row">
                      <p><span>Date</span>: {{ $item->date }}</p>
                  </div>
                  <div class="bio-row">
                      <p><span>Status </span>: <span class="text-success f-bold">{{ $item->item_status }}</span></p>
                  </div>
                  <div class="bio-row">
                      <p><span>Email </span>: </p>
                  </div>
                  <div class="bio-row">
                      <p><span>Contact  </span>: {{ $project->client_contact }} </p>
                  </div>
                 
              </div>
          </div>
      </div>
              </div>
     
     </div>
     <div class="mb-2">
       <div class="col-md-12">
               <h5>Description </h5>
              </div>
     <div class="card mb-3">
         <div class="bio-graph-heading">
              
             <p>{{ $item->notes }}</p>
          </div>
     </div>
     </div>
     
      <div class="row mb-2">
           <div class="col-lg-12 mb-2">
              <h5>Item Photo / Documents</h5>
         </div>
         <div class="col-lg-2"> <img alt="image" src="{{ asset('public/public/'.$item->image) }}" style="width:100%"></div>
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