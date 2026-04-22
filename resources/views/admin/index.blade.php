<!DOCTYPE html>
<html lang="zxx">

<head>
    <title>Car equip</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <!-- External CSS libraries -->
    <link type="text/css" rel="stylesheet" href="{{ asset('public/assets1/css/bootstrap.min.css') }}">
    <link type="text/css" rel="stylesheet" href="{{ asset('public/assets1/fonts/font-awesome/css/font-awesome.min.css') }}">
    <link type="text/css" rel="stylesheet" href="{{ asset('public/assets/fonts/flaticon/font/flaticon.css') }}">

    <!-- Favicon icon -->
    <link rel="shortcut icon" href="{{ asset('public/assets/img/favicon.jpeg') }}" type="image/png" >

    <!-- Google fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@300;400;500;600;700;800;900&amp;display=swap" rel="stylesheet">

    <!-- Custom Stylesheet -->
    <link type="text/css" rel="stylesheet" href="{{ asset('public/assets1/css/style.css') }}">

</head>
<body id="top">
<div class="page_loader"></div>

<!-- Login 30 start -->
<div class="login-30 tab-box">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-6 col-md-12 bg-img">
                <div class="informeson">
                    
                    <h2><span>Bienvenue chez Car Equip</span></h2>
              @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif


@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif      
                  
                </div>
            </div>
            <div class="col-lg-6 col-md-12 form-section">
    <div class="login-inner-form">
        <div class="details">
            <div class="logo-2">
                <a href="#">
                    <img src="{{ asset('public/assets/img/logo/logo.png') }}" alt="logo" style="width:200px">
                </a>
            </div>

            <h1>Connectez-vous à votre compte</h1>
           
            <form action="{{route('admin.login.post')}}" method="post">
                 @csrf
                <div class="form-group">
                    <label for="first_field" class="form-label float-start">Adresse e-mail</label>
                    <input name="email" type="email" value="{{old('email')}}" class="form-control" id="first_field" placeholder="Adresse e-mail" aria-label="Adresse e-mail">
                </div>
                <div class="form-group clearfix">
                    <label for="second_field" class="form-label float-start">Mot de passe</label>
                    <input name="password" type="password" class="form-control" autocomplete="off" id="second_field" placeholder="Mot de passe" aria-label="Mot de passe">
                </div>
                <div class="checkbox form-group clearfix">
                    <div class="form-check float-start">
                        <input class="form-check-input" type="checkbox" id="rememberme">
                        <label class="form-check-label" for="rememberme">
                            Se souvenir de moi
                        </label>
                    </div>
                    <a href="forgot-password-30.html" class="link-light float-end forgot-password">Mot de passe oublié ?</a>
                </div>
                <div class="form-group clearfix">
                    <button type="submit" class="btn btn-lg btn-primary btn-theme"><span>Connexion</span></button>
                </div>
            </form>
            
        </div>
    </div>
</div>

        </div>
    </div>
</div>
<!-- Login 30 end -->

<!-- External JS libraries -->
<script src="{{ asset('public/assets1/js/jquery-3.6.0.min.js') }}"></script>
<script src="{{ asset('public/assets1/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('public/assets1/js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('public/assets1/js/app.js') }}"></script>
<!-- Custom JS Script -->

</body>


</html>
