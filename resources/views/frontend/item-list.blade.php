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
                  <h4>Item List </h4>
                                      @if ($errors->has('error'))
    <div class="alert alert-danger">
        {{ $errors->first('error') }}
    </div>
@endif
@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
                  <div>
                  <!--<button class="theme-btn" style="background-color: #173f5f !important; border-color:#173f5f !important; cursor:pointer;"><i class="fa fa-filter"></i> Assigned To</button>-->
                  <a href="{{ url('user/create-item/'.$project_id) }}" class="theme-btn">Create Item</a>
                  </div>
              </div>
               <div class="col-md-5" >
                   @php
                   $project = DB::table('projects')->where('id', $project_id)->first();
                   @endphp
                   <h4>{{ $project->project_name }}</h4>
                </div>
              <div class="col-md-7" style="text-align:right">
                  <div class="tab">
  <button class="tablinks" onclick="openCity(event, '1')" id="defaultOpen">All</button>
  <button class="tablinks" onclick="openCity(event, '2')">Open </button>
  <button class="tablinks" onclick="openCity(event, '3')">In Progress</button>
  <button class="tablinks" onclick="openCity(event, '4')">Closed</button>
</div>
 </div>    
 </div>
 <div class="row">
 
                  <div id="1" class="tabcontent">
                      <!-- <div class="col-md-12  mb-2" style="text-align:right;">-->
                      <!--<button class="btn-sm btn btn-primary"><i class="fa fa-file-export"></i> Export</button>-->
                      <!--</div>-->
                      @foreach($items as $item)
   <div class="col-md-12">  
               <a href="{{ url('user/item-details/'.$item->id) }}">   
                <div class="card mb-2">
                      <div class="card-body">
                          <div class="bio-chart">
                             <div style="display:inline;width:80px;height:80px;">
                                  <img alt="image" src="{{ asset('public/public/'.$item->image) }}" style="width:100%">
								 
                                  </div>
                          </div>
                         
                          <div class="bio-desk">
                              <h4>{{ $item->item_name }}</h4> 
                             <a href="{{ url('user/edit-item/'.$item->id) }}" class="btn btn-success" >Edit</a>
                              <p  class="mb-0"><strong>Description:</strong> {{ $item->notes }} </p>
                              <p class="mb-0"><strong>Assigned To: </strong> {{ $item->assign_to }}</p>
                             <p class="mb-0">  </p>
                             <p class="mb-0"><strong>Date:</strong> <span class="mr-3"> {{ $item->date }}</span> <strong>Status:</strong> <span class="text-success f-bold">{{ $item->item_status }}</span> </p>
                          </div>
                          
                         
                      </div>
                  </div>
                  </a>
              </div>
            @endforeach
           
</div>

<div id="2" class="tabcontent">
    <!--<div class="col-md-12  mb-2" style="text-align:right;">-->
    <!--                  <button class="btn-sm btn btn-primary"><i class="fa fa-file-export"></i> Export</button>-->
    <!--                  </div>-->
     @foreach($items as $item)
     @if($item->item_status == 'OPEN')
   <div class="col-md-12">  
               <a href="{{ url('user/item-details/'.$item->id) }}">   
                <div class="card mb-2">
                      <div class="card-body">
                          <div class="bio-chart">
                             <div style="display:inline;width:80px;height:80px;">
                                  <img alt="image" src="{{ asset('public/public/'.$item->image) }}" style="width:100%">
								 
                                  </div>
                          </div>
                          <div class="bio-desk">
                              <h4>{{ $item->item_name }}</h4>
                             
                              <p class="mb-0"><strong>Description:</strong> {{ $item->notes }} </p>
                              <p class="mb-0"><strong>Assigned To: </strong> {{ $item->assign_to }}</p>
                             <p class="mb-0"><strong>Date:</strong> <span class="mr-3"> {{ $item->date }}</span> <strong>Status:</strong> <span class="text-success f-bold">{{ $item->item_status }}</span> </p>
                          </div>
                      </div>
                  </div>
                  </a>
              </div>
              @endif
            @endforeach
              
</div>

<div id="3" class="tabcontent">
    <!--<div class="col-md-12  mb-2" style="text-align:right;">-->
    <!--                  <button class="btn-sm btn btn-primary"><i class="fa fa-file-export"></i> Export</button>-->
    <!--                  </div>-->
    @foreach($items as $item)
     @if($item->item_status == 'INPROGRESS')
   <div class="col-md-12">  
               <a href="{{ url('user/item-details/'.$item->id) }}">   
                <div class="card mb-2">
                      <div class="card-body">
                          <div class="bio-chart">
                             <div style="display:inline;width:80px;height:80px;">
                                  <img alt="image" src="{{ asset('public/public/'.$item->image) }}" style="width:100%">
								 
                                  </div>
                          </div>
                          <div class="bio-desk">
                              <h4>{{ $item->item_name }}</h4>
                             
                              <p class="mb-0"><strong>Description:</strong> {{ $item->notes }} </p>
                              <p class="mb-0"><strong>Assigned To: </strong> {{ $item->assign_to }}</p>
                             <p class="mb-0"><strong>Date:</strong> <span class="mr-3"> {{ $item->date }}</span> <strong>Status:</strong> <span class="text-success f-bold">{{ $item->item_status }}</span> </p>
                          </div>
                      </div>
                  </div>
                  </a>
              </div>
              @endif
            @endforeach
</div>

<div id="4" class="tabcontent">
    <!--<div class="col-md-12  mb-2" style="text-align:right;">-->
    <!--                  <button class="btn-sm btn btn-primary"><i class="fa fa-file-export"></i> Export</button>-->
    <!--                  </div>-->
  @foreach($items as $item)
     @if($item->item_status == 'CLOSED')
   <div class="col-md-12">  
               <a href="{{ url('user/item-details/'.$item->id) }}">   
                <div class="card mb-2">
                      <div class="card-body">
                          <div class="bio-chart">
                             <div style="display:inline;width:80px;height:80px;">
                                  <img alt="image" src="{{ asset('public/public/'.$item->image) }}" style="width:100%">
								 
                                  </div>
                          </div>
                          <div class="bio-desk">
                              <h4>{{ $item->item_name }}</h4>
                             
                              <p class="mb-0"><strong>Description:</strong> {{ $item->notes }} </p>
                              <p class="mb-0"><strong>Assigned To: </strong> {{ $item->assign_to }}</p>
                             <p class="mb-0"><strong>Date:</strong> <span class="mr-3"> {{ $item->date }}</span> <strong>Status:</strong> <span class="text-success f-bold">{{ $item->item_status }}</span> </p>
                          </div>
                      </div>
                  </div>
                  </a>
              </div>
              @endif
            @endforeach
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
 <script>
function openCity(evt, cityName) {
  var i, tabcontent, tablinks;
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }
  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }
  document.getElementById(cityName).style.display = "block";
  evt.currentTarget.className += " active";
}

// Get the element with id="defaultOpen" and click on it
document.getElementById("defaultOpen").click();
</script>
</body>


</html>