 <!-- header area -->
    <header class="header">

       

        <!-- navbar -->
        <div class="main-navigation">
            <nav class="navbar navbar-expand-lg">
                <div class="container position-relative">
                    <a class="navbar-brand" href="{{ url('/') }}">
                        <img src="{{ asset('assets_web/img/logo.png') }}" alt="logo" style="width:100px">
                    </a>
                    <div class="mobile-menu-right">
                        <div class="mobile-menu-btn">
                            <button type="button" class="nav-right-link search-box-outer"><i
                                    class="far fa-search"></i></button>
                        </div>
                        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas"
                            data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar"
                            aria-label="Toggle navigation">
                            <span></span>
                            <span></span>
                            <span></span>
                        </button>
                    </div>
                    <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasNavbar"
                        aria-labelledby="offcanvasNavbarLabel">
                        <div class="offcanvas-header">
                            <a href="{{ url('/') }}" class="offcanvas-brand" id="offcanvasNavbarLabel">
                                <img src="{{ asset('assets_web/img/logo.png') }}" alt="">
                            </a>
                            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"><i
                                    class="far fa-xmark"></i></button>
                        </div>
                        <div class="offcanvas-body gap-xl-4">
                            <ul class="navbar-nav justify-content-end flex-grow-1">
                               
                                <li class="nav-item"><a class="nav-link" href="{{ url('/') }}">Home</a></li>
                                <li class="nav-item"><a class="nav-link" href="{{ url('/pricing') }}">Pricing</a></li>
                                <li class="nav-item"><a class="nav-link" href="#">About Us</a></li>
                                <li class="nav-item"><a class="nav-link" href="{{ url('/contact') }}">Contact Us</a></li>
                                <!--<li class="nav-item"><a class="nav-link" href="#">Dashboard</a></li>-->
                            </ul>
                            <!-- nav-right -->
                              @guest
                            <div class="nav-right">
                                <div class="search-btn">
                                    <button type="button" class="nav-right-link search-box-outer"><i
                                            class="far fa-search"></i></button>
                                </div>
                                <div class="nav-btn">
                                    <a href="{{ url('/login') }}" class="theme-btn">Login<i
                                            class="fas fa-user-circle"></i></a>
                                </div>
                                
                            </div>
                            @endguest
                            @auth
                               <div class="nav-btn" style="margin-top: 30px;">
                                    <div class="user-dropdown">
    <p><a href="#"> <img src="https://www.bootdey.com/img/Content/avatar/avatar1.png" class="radius-100"/> </a> <small class="f-bold">{{ Auth::user()->full_name }}</small></p>
    <div class="dropdown-content">
      <a href="{{ url('/profile') }}">Profile</a>
     
      <a href="{{ url('/logout') }}">Logout</a>
    </div>
  </div>
                                </div>
        @endauth
                        </div>
                    </div>
                </div>
            </nav>
        </div>
        <!-- navbar end-->

    </header>
    <!-- header area end -->