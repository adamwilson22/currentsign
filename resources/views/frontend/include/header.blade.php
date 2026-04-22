
<!doctype html>
<html lang="en">



<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="{{ asset('assets_web/img/v.svg') }}" type="image/svg+xml">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('assets_web/assets/bootstrap/css/bootstrap.min.css') }}">
    <!-- icon css-->
    <link rel="stylesheet" href="{{ asset('assets_web/assets/elagent-icon/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets_web/assets/animation/animate.css') }}">
    <link rel="stylesheet" href="{{ asset('assets_web/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets_web/css/responsive.css') }}">
    <link rel="stylesheet" href="{{ asset('assets_web/assets/slick/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('assets_web/assets/slick/slick-theme.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Current Sign</title>
</head>

<body data-scroll-animation="true">
<div id="preloader">
    <div id="ctn-preloader" class="ctn-preloader">
        <div class="round_spinner">
            <div class="spinner"></div>
            <div class="text">
                <img src="{{ asset('assets_web/img/logo.png') }}" alt="" style="width:100px">
             
            </div>
        </div>
       
    </div>
</div>
<div class="body_wrapper">
    <nav class="navbar navbar-expand-lg menu_two" id="sticky">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <img src="{{ asset('assets_web/img/logo.png') }}"  alt="logo" style="width:100px">
            </a>
            <button class="navbar-toggler collapsed" type="button" data-toggle="collapse"
                    data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                    aria-label="Toggle navigation">
                    <span class="menu_toggle">
                        <span class="hamburger">
                            <span></span>
                            <span></span>
                            <span></span>
                        </span>
                        <span class="hamburger-cross">
                            <span></span>
                            <span></span>
                        </span>
                    </span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav menu dk_menu ml-auto">
                  
                    <li class="nav-item">
                        <a href="{{ url('/') }}" class="nav-link">Home</a>
                    </li>
                     <li class="nav-item">
                        <a href="#" class="nav-link">About Us</a>
                    </li>

                     <li class="nav-item">
                        <a href="{{ url('/pricing') }}" class="nav-link">Pricing Plan</a>
                    </li>
                     <li class="nav-item">
                        <a href="{{ url('/contact') }}" class="nav-link">Contact Us</a>
                    </li>
                
                </ul>
                @guest
    <a class="nav_btn btn btn-primary" href="{{ url('/register') }}">Free Trial</a>
    <a class="nav_btn btn btn-primary ml-2" href="{{ url('/login') }}">
        <i class="icon_profile"></i> Log In
    </a>
@endguest

@auth
 <a class="nav_btn btn btn-primary ml-2" href="{{ url('user/dashboard') }}">
        <i class="icon_profile"></i> Dashboard
    </a>
@endauth

            </div>
        </div>
    </nav>