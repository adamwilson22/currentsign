<!DOCTYPE html>
<html lang="en" dir="ltr">


<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />

	<title>current sign</title>
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<!-- GOOGLE FONTS -->
	<link rel="preconnect" href="https://fonts.googleapis.com/">
	<link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
	
	<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@200;300;400;500;600;700;800&amp;family=Poppins:wght@300;400;500;600;700;800;900&amp;family=Roboto:wght@400;500;700;900&amp;display=swap" rel="stylesheet"> 

	<link href="https://cdn.jsdelivr.net/npm/@mdi/font@4.4.95/css/materialdesignicons.min.css" rel="stylesheet" />

	<!-- PLUGINS CSS STYLE -->
	<link href="{{ asset('public/assets/plugins/simplebar/simplebar.css') }}" rel="stylesheet" />

	<!-- Data Tables -->
	<link href="{{ asset('public/assets/plugins/data-tables/datatables.bootstrap5.min.css') }}" rel='stylesheet'>
	<link href="{{ asset('public/assets/plugins/data-tables/responsive.datatables.min.css') }}" rel='stylesheet'>

	<!-- ekka CSS -->
	<link id="ekka-css" rel="stylesheet" href="{{ asset('public/assets/css/ekka.css') }}" />

	<!-- FAVICON -->
	<link href="{{ asset('public/assets/img/favicon.png') }}" rel="shortcut icon" />
</head>

<body class="ec-header-fixed ec-sidebar-fixed ec-sidebar-dark ec-header-light" id="body">