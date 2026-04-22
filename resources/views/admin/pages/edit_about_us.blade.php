@include('admin.include.header')
   <style>
        #preview img{
            width:100%;
        }
        #preview1 img{
            width:100%;
        }
        
        #cke_notifications_area_description{
            display:none;
        }
    </style>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBQDSvBppnW59UJ0ALOlGV5aMiJl6bgk70&callback=initMap&sensor=false&libraries=places"></script>

    <script>


  function initialize() {
        var address = (document.getElementById('pac-input'));
        var autocomplete = new google.maps.places.Autocomplete(address);
        autocomplete.setTypes(['geocode']);
        google.maps.event.addListener(autocomplete, 'place_changed', function() {
            var place = autocomplete.getPlace();
            if (!place.geometry) {
                return;
            }

        var address = '';
        if (place.address_components) {
            address = [
                (place.address_components[0] && place.address_components[0].short_name || ''),
                (place.address_components[1] && place.address_components[1].short_name || ''),
                (place.address_components[2] && place.address_components[2].short_name || '')
                ].join(' ');
        }
        /*********************************************************************/
        /* var address contain your autocomplete address *********************/
        /* place.geometry.location.lat() && place.geometry.location.lat() ****/
        /* will be used for current address latitude and longitude************/
        /*********************************************************************/
      

        document.getElementById('latitude').value = place.geometry.location.lat();
        document.getElementById('longnitude').value = place.geometry.location.lng();
        });
  }

   google.maps.event.addDomListener(window, 'load', initialize);
   
  
    </script>	
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
        <h1>About US</h1>
        <p class="breadcrumbs"><span><a href="index.html">Home</a></span>
            <span><i class="mdi mdi-chevron-right"></i></span>About US</p>
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
									<h2>About US</h2>
							
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

      <form method="POST" action="{{ route('about_us.save', ['id' => $id]) }}" enctype="multipart/form-data" >
           @csrf
     
								<div class="card-body">
									<div class="row ec-vendor-uploads">
										 
										<div class="col-lg-12">
    <div class="ec-vendor-upload-detail">
        <div class="row">
            
            <div class="col-md-12">
               <label for="firstName"></label>
               <textarea class="form-control" name="about_us_text" id="description">{{ $about_us->about_us_text }}</textarea>
            </div>
          
         
            <div class="col-md-12 mt-4">
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
	<script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
<script>
    CKEDITOR.replace('description'); // Enable CKEditor for the description field
</script>
	</body>


</html>