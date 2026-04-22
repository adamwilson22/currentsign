<!DOCTYPE html>
<html lang="en" dir="ltr">


<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	

	<title>current sign</title>

	<!-- GOOGLE FONTS -->
	<link rel="preconnect" href="https://fonts.googleapis.com/">
	<link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@200;300;400;500;600;700;800&amp;family=Poppins:wght@300;400;500;600;700;800;900&amp;family=Roboto:wght@400;500;700;900&amp;display=swap" rel="stylesheet"> 

	<link href="https://cdn.jsdelivr.net/npm/@mdi/font@4.4.95/css/materialdesignicons.min.css" rel="stylesheet" />

	<!-- PLUGINS CSS STYLE -->
	<link href="{{ asset('public/assets/plugins/daterangepicker/daterangepicker.css') }}" rel="stylesheet">
	<link href="{{ asset('public/assets/plugins/simplebar/simplebar.css') }}" rel="stylesheet" />

	<!-- Ekka CSS -->
	<link href="{{ asset('public/assets/css/ekka.css') }}" rel="stylesheet" />

	<!-- FAVICON -->
	<link href="{{ asset('public/assets/img/favicon.png') }}" rel="shortcut icon" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

</head>

<body class="ec-header-fixed ec-sidebar-fixed ec-sidebar-dark ec-header-light" id="body">

	<!--  WRAPPER  -->
	<div class="wrapper">
		
		<!-- LEFT MAIN SIDEBAR -->
	 @include('admin.include.sidebar')

		<!--  PAGE WRAPPER -->
		<div class="ec-page-wrapper">

			<!-- Header -->
				@include('admin.include.header-nav')



			<!-- CONTENT WRAPPER -->
			<div class="ec-content-wrapper">
				<div class="content">
					<!-- Top Statistics -->
					<div class="row">
	<div class="col-xl-4 col-sm-6 p-b-15 lbl-card">
		<div class="card card-mini dash-card card-1">
			<div class="card-body">
				<h2 class="mb-1">{{ $userCount }}</h2> 
				<p>Total Users </p>
				<span class="mdi mdi-account-arrow-left"></span>
			</div>
		</div>
	</div>
	<!-- <div class="col-xl-3 col-sm-6 p-b-15 lbl-card">
		<div class="card card-mini dash-card card-2">
			<div class="card-body">
				<h2 class="mb-1">79,503</h2>
				<p>Visiteurs <br>quotidiens</p>
				<span class="mdi mdi-account-clock"></span>
			</div>
		</div>
	</div> -->
	
	<div class="col-xl-4 col-sm-6 p-b-15 lbl-card">
		<div class="card card-mini dash-card card-4">
			<div class="card-body">
				<h2 class="mb-1">{{ $signatures }}</h2>
				<p>Total Signatures Pdf</p>
				<span class="fa fa-money" style="padding-top:10px"></span>
			</div>
		</div>
	</div>
</div>


				

				

				


					
				</div> <!-- End Content -->
			</div> <!-- End Content Wrapper -->
			
			
			

			<!-- Footer -->
			<footer class="footer mt-auto">
				<div class="copyright bg-white">
					<p>
						Copyright &copy; <span id="ec-year"></span> Car Equip. All Rights Reserved.
					  </p>
				</div>
			</footer>

		</div> <!-- End Page Wrapper -->
	</div> <!-- End Wrapper -->

	<!-- Common Javascript -->
	<script src="{{ asset('public/assets/plugins/jquery/jquery-3.5.1.min.js') }}"></script>
	<script src="{{ asset('public/assets/js/bootstrap.bundle.min.js') }}"></script>
	<script src="{{ asset('public/assets/plugins/simplebar/simplebar.min.js') }}"></script>
	<script src="{{ asset('public/assets/plugins/jquery-zoom/jquery.zoom.min.js') }}"></script>
	<script src="{{ asset('public/assets/plugins/slick/slick.min.js') }}"></script>

	<!-- Chart -->
	<script src="{{ asset('public/assets/plugins/charts/Chart.min.js') }}"></script>
	<script src="{{ asset('public/assets/js/chart.js') }}"></script>

	<!-- Google map chart -->
	<script src="{{ asset('public/assets/plugins/charts/google-map-loader.js') }}"></script>
	<script src="{{ asset('public/assets/plugins/charts/google-map.js') }}"></script>

	<!-- Date Range Picker -->
	<script src="{{ asset('public/assets/plugins/daterangepicker/moment.min.js') }}"></script>
    <script src="{{ asset('public/assets/plugins/daterangepicker/daterangepicker.js') }}"></script>
	<script src="{{ asset('public/assets/js/date-range.js') }}"></script>

	
	<!-- Ekka Custom -->
	<script src="{{ asset('public/assets/js/ekka.js') }}"></script>

</body>


</html>