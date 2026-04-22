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
                  <h4>Projects </h4>
                  
                  <a href="{{ url('user/create-project') }}" class="theme-btn">Create Project</a>
              </div>
              
              <div class="col-md-12" style="text-align:right">
                  <div class="tab">
  <button class="tablinks" onclick="openCity(event, '1')" id="defaultOpen">All</button>
  <button class="tablinks" onclick="openCity(event, '2')">Open </button>
  <button class="tablinks" onclick="openCity(event, '3')">In Progress</button>
  <button class="tablinks" onclick="openCity(event, '4')">Closed</button>
</div>
 </div>                 
                  <div id="1" class="tabcontent">
                      @foreach($projects as $project)
        <div class="col-md-12">  
                  <div class="card mb-2">
                      <div class="card-body">
                          <div class="bio-chart">
                             <div style="display:inline;width:80px;height:80px;">
                                 <a href="{{ url('user/item-list/'.$project->id) }}"><img alt="image" src="{{ asset('public/public/'.$project->image) }}" style="width:100%"></a> 
								 
                                  </div>
                          </div>
                          <div class="bio-desk">
                              <a href="{{ url('user/item-list/'.$project->id) }}"><h4>{{ $project->project_name }}</h4></a>
                              <p class="mb-0"><strong>Project Manager :</strong>{{ $project->project_name }}</p>
                              <p class="mb-0"><strong>No. Of Items:</strong> 0</p>
                              <p class="mb-0"><strong>No. Of Open Items:</strong> 0</p>
                              <p class="mb-0"><strong>Status:</strong> <span class="text-success f-bold">{{ $project->project_status }}</span></p>
                          </div>
                      </div>
                  </div>
              </div>
              @endforeach
</div>

<div id="2" class="tabcontent">
         @foreach($projects as $project)
        <div class="col-md-12">  
                  <div class="card mb-2">
                      <div class="card-body">
                          <div class="bio-chart">
                             <div style="display:inline;width:80px;height:80px;">
                                 <a href="{{ url('user/item-list/'.$project->id) }}"><img alt="image" src="{{ asset('public/public/'.$project->image) }}" style="width:100%"></a> 
								 
                                  </div>
                          </div>
                          <div class="bio-desk">
                              <a href="{{ url('user/item-list/'.$project->id) }}"><h4>{{ $project->project_name }}</h4></a>
                              <p class="mb-0"><strong>Project Manager :</strong>{{ $project->project_name }}</p>
                              <p class="mb-0"><strong>No. Of Items:</strong> 0</p>
                              <p class="mb-0"><strong>No. Of Open Items:</strong> 0</p>
                              <p class="mb-0"><strong>Status:</strong> <span class="text-success f-bold">{{ $project->project_status }}</span></p>
                          </div>
                      </div>
                  </div>
              </div>
              @endforeach
</div>

<div id="3" class="tabcontent">
         @foreach($projects as $project)
         @if($project->project_status == 'INPROGRESS')
        <div class="col-md-12">  
                  <div class="card mb-2">
                      <div class="card-body">
                          <div class="bio-chart">
                             <div style="display:inline;width:80px;height:80px;">
                                 <a href="{{ url('user/item-list/'.$project->id) }}"><img alt="image" src="{{ asset('public/public/'.$project->image) }}" style="width:100%"></a> 
								 
                                  </div>
                          </div>
                          <div class="bio-desk">
                              <a href="{{ url('user/item-list/'.$project->id) }}"><h4>{{ $project->project_name }}</h4></a>
                              <p class="mb-0"><strong>Project Manager :</strong>{{ $project->project_name }}</p>
                              <p class="mb-0"><strong>No. Of Items:</strong> 0</p>
                              <p class="mb-0"><strong>No. Of Open Items:</strong> 0</p>
                              <p class="mb-0"><strong>Status:</strong> <span class="text-success f-bold">{{ $project->project_status }}</span></p>
                          </div>
                      </div>
                  </div>
              </div>
              @endif
              @endforeach
</div>

<div id="4" class="tabcontent">
       @foreach($projects as $project)
        @if($project->project_status == 'CLOSED')
        <div class="col-md-12">  
                  <div class="card mb-2">
                      <div class="card-body">
                          <div class="bio-chart">
                             <div style="display:inline;width:80px;height:80px;">
                                 <a href="{{ url('user/item-list/'.$project->id) }}"><img alt="image" src="{{ asset('public/public/'.$project->image) }}" style="width:100%"></a> 
								 
                                  </div>
                          </div>
                          <div class="bio-desk">
                              <a href="{{ url('user/item-list/'.$project->id) }}"><h4>{{ $project->project_name }}</h4></a>
                              <p class="mb-0"><strong>Project Manager :</strong>{{ $project->project_name }}</p>
                              <p class="mb-0"><strong>No. Of Items:</strong> 0</p>
                              <p class="mb-0"><strong>No. Of Open Items:</strong> 0</p>
                              <p class="mb-0"><strong>Status:</strong> <span class="text-success f-bold">{{ $project->project_status }}</span></p>
                          </div>
                      </div>
                  </div>
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