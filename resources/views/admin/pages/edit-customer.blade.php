@include('admin.include.header')
	<!-- WRAPPER -->
	<div class="wrapper">

			@include('admin.include.sidebar')

		<!--  PAGE WRAPPER -->
		<div class="ec-page-wrapper">

			@include('admin.include.header-nav')
		
					<!-- CONTENT WRAPPER -->
			<div class="ec-content-wrapper">
				<div class="content">
					<div class="breadcrumb-wrapper d-flex align-items-center justify-content-between">
    <div>
        <h1>Edit Customer</h1>
        <p class="breadcrumbs"><span><a href="index.html">Home</a></span>
            <span><i class="mdi mdi-chevron-right"></i></span>Edit Customer</p>
    </div>
    <div>
        <a href="{{ route('customers') }}" class="btn btn-primary"> Back
        </a>
    </div>
</div>
       @if(session('message'))
    <div class="alert alert-success">
        {{ session('message') }}
    </div>
@endif
					<div class="row">
						<div class="col-12">
							<div class="card card-default">
								<div class="card-header card-header-border-bottom">
									<h2>Edit Customer</h2>
							
								</div>
										@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

      <form method="POST" action="{{ route('customers.update') }}" enctype="multipart/form-data" >
           @csrf
								<div class="card-body">
									<div class="row ec-vendor-uploads">
										  <div class="col-lg-4">
                        <div class="ec-vendor-img-upload">
                            <div class="ec-vendor-main-img">
                                <div class="avatar-upload">
                                    <div class="avatar-edit">
                                        <input type='file' id="imageUpload" name="image" class="ec-image-upload" accept=".png, .jpg, .jpeg" />
                                        <label for="imageUpload"><img src="{{ asset('public/assets/img/icons/edit.svg') }}" class="svg_img header_svg" name="image" alt="edit" /></label>
                                    </div>
                                    <div class="avatar-preview ec-preview">
                                        <div class="imagePreview ec-div-preview">
                                            <img class="ec-image-preview" name="image" src="{{ asset('public/assets/img/products/vender-upload-preview.jpg') }}" alt="edit" />
                                        </div>
                                    </div>
                                </div>
                                <div class="thumb-upload-set colo-md-12">
                                    <!-- Thumbnail upload section -->
                                </div>
                            </div>
                        </div>
                    </div>
										<div class="col-lg-8">
    <div class="ec-vendor-upload-detail">
        <div class="row g-3">
            <div class="col-md-12">
               <label for="firstName">Full Name</label>
                 <input type="hidden" class="form-control" name="id" id="firstName" value="{{ $user->id }}">
               
              <input type="text" class="form-control" name="full_name" id="firstName" value="{{ $user->full_name }}">
            </div>
           
            <!--<div class="col-md-12">
             <label for="lastName">Address</label>
             <input type="text" class="form-control" name="address"  id="lastName" value="">
            </div>-->
             <div class="col-md-12">
             <label for="lastName">Email</label>
             <input type="email" class="form-control" name="email"  id="lastName" value="{{ $user->email }}">
            </div>
            <div class="col-md-12">
                    <label for="lastName">Password</label> 
                    <input type="password" class="form-control"  name="password"  id="lastName" value="{{ $user->password }}">
            </div>
            
            <div class="col-md-12">
               <label for="firstName">DOB</label>
              <input type="text" class="form-control" name="dob" id="" value="{{ $user->dob }}">
            </div>
            
            
            
            <div class="col-md-12">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
            </form>
        </div>
    </div>
</div>

									</div>
								</div>
							</div>
						</div>
					</div>
				</div> <!-- End Content -->
			</div> <!-- End Content Wrapper -->

	@include('admin.include.footer')
	</body>


</html>