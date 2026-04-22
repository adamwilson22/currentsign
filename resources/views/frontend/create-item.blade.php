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
                 <div style="float: right;" >
                  <!--<button class="theme-btn" style="background-color: #173f5f !important; border-color:#173f5f !important; cursor:pointer;"><i class="fa fa-filter"></i> Assigned To</button>-->
                  <a href="{{ url('user/item-list/'.$project_id) }}" class="theme-btn">Back</a>
                  </div>
                   @php
                   $project = DB::table('projects')->where('id', $project_id)->first();
                   @endphp
    <h4>{{ $project->project_name }}</h4>
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
    <hr/>
      <div>
           
          <div class="row">
              <div class="col-md-12 mt-3  mb-2 d-flex" style="justify-content: space-between;">
                  <h5>Create Item</h5>
                  
                 
              </div>
              <div class="col-md-12">
      <form action="{{ url('user/save-item/'.$project_id) }}" method="POST"  enctype="multipart/form-data"  >
          @csrf
          <input type="hidden" name="redirect_type" id="redirect_type" value="list">
        <div class="row">
            <div class="form-group col-lg-12 mb-3">
                <label>Item Name</label>
                <input type="text" name="item_name" class="form-control">
            </div>
            <div class="form-group col-lg-12 mb-3">
                <label>Assign To  </label>
                <input type="text" name="assign_to" class="form-control">
            </div>
           
            
            <div class="form-group col-lg-12 mb-3">
                <label>Date </label>
                <input type="date" name="date" class="form-control" required>
            </div>
            
            <div class="form-group col-lg-12 mb-3">
                <label>Add Contractor Notes</label>
				<textarea class="form-control" name="notes"  ></textarea>
            </div>
           <div class="form-group col-lg-6 mb-3">
                <label>Upload Photo / Document</label>
                <input type="file" name="image"  class="form-control">
            </div>
             <div class="form-group col-lg-6 mb-3">
                <label>Status</label>
                <select  name="item_status" class="form-control" required>
                    <option value="OPEN" >Open</option>
                    <option value="INPROGRESS" >Inprogress</option>
                    <option value="CLOSED" >Closed</option>
                </select>
            </div>
            <div class="form-group col-lg-12">
               
                 <button type="submit" class="btn btn-success" onclick="document.getElementById('redirect_type').value='list'">Save</button>
            <button type="submit" class="btn btn-success" onclick="document.getElementById('redirect_type').value='add'">Save & Add</button>
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