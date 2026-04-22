<!DOCTYPE html>
<html lang="en">
   <head>
      <!-- Required meta tags -->
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <title>Admin</title>
      <!-- plugins:css -->
      <link rel="stylesheet" href="<?php echo url("/"); ?>/resources/views/admin/assets/vendors/iconfonts/font-awesome/css/all.min.css">
      <link rel="stylesheet" href="<?php echo url("/"); ?>/resources/views/admin/assets/vendors/css/vendor.bundle.base.css">
      <link rel="stylesheet" href="<?php echo url("/"); ?>/resources/views/admin/assets/vendors/css/vendor.bundle.addons.css">
      <!-- endinject -->
      <!-- plugin css for this page -->
      <!-- End plugin css for this page -->
      <!-- inject:css -->
      <link rel="stylesheet" href="<?php echo url("/"); ?>/resources/views/admin/assets/css/style.css">
      <!-- endinject -->
      <link rel="shortcut icon" href="<?php echo url("/"); ?>/resources/views/admin/assets/images/favicon.png" />
  
  <script src="https://www.google.com/recaptcha/enterprise.js?render=6LciikwqAAAAANxBD08rw3EQ923zuP3bLof4KSo4"></script>

   </head>
   <body>
      <div class="container-scroller">
         <div class="container-fluid page-body-wrapper full-page-wrapper">
            <div class="content-wrapper d-flex align-items-center auth">
               <div class="row w-100">
                  <div class="col-lg-4 mx-auto">
                     <div class="auth-form-light text-left p-5">
                        <div class="brand-logo">
                           <!--<img src="<?php echo url("/"); ?>/resources/views/admin/assets/images/logo.svg" alt="logo">-->
                           
                           <h1> Administrator</h1>
                           
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
                        <h4>Welcome! Let's get started</h4>
                        <h6 class="font-weight-light">Log in to continue.</h6>
                        <form class="pt-3" action="{{route('admin.login.post')}}"  method="post" >
                            @csrf
                           <div class="form-group">
                              <input type="email" name="email" class="form-control form-control-lg" id="exampleInputEmail1" value="{{old('email')}}" placeholder="Username">
                           </div>
                           <div class="form-group">
                              <input type="password" name="password" class="form-control form-control-lg" id="exampleInputPassword1" placeholder="Password">
                           </div>
                           
                           <div class="mt-3">
                              <button  style="line-height: 0px !important;" type="submit" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn" href="../../index-2.html">Login</button>
                           </div>
<!--                           <button class="g-recaptcha"-->
<!--    data-sitekey="6LciikwqAAAAANxBD08rw3EQ923zuP3bLof4KSo4"-->
<!--    data-callback='onSubmit'-->
<!--    data-action='submit'>-->
<!--  Submit-->
<!--</button>-->
                           
                           <div class="my-2 d-flex justify-content-between align-items-center">
                              <div class="form-check">
                                 <label class="form-check-label text-muted">
                                 <input type="checkbox" class="form-check-input">
                                 Keep me connected
                                 </label>
                              </div>
                              <!--<a type="submit"  href="#" class="auth-link text-black">Forgot your password ?</a>-->
                           </div>
                           
                           <!--<div class="mb-2">-->
                           <!--   <button type="button" class="btn btn-block btn-facebook auth-form-btn">-->
                           <!--   <i class="fab fa-facebook-f mr-2"></i>Connect using facebook-->
                           <!--   </button>-->
                           <!--</div>-->
                           
                           <!--<div class="text-center mt-4 font-weight-light">-->
                           <!--   Don't have an account? <a href="register.html" class="text-primary">Create</a>-->
                           <!--</div>-->
                           
                        </form>
                     </div>
                  </div>
               </div>
            </div>
            <!-- content-wrapper ends -->
         </div>
         <!-- page-body-wrapper ends -->
      </div>
      <!-- container-scroller -->
      <!-- plugins:js -->
      <script src="<?php echo url("/"); ?>/resources/views/admin/assets/vendors/js/vendor.bundle.base.js"></script>
      <script src="<?php echo url("/"); ?>/resources/views/admin/assets/vendors/js/vendor.bundle.addons.js"></script>
      <!-- endinject -->
      <!-- inject:js -->
      <script src="<?php echo url("/"); ?>/resources/views/admin/assets/js/off-canvas.js"></script>
      <script src="<?php echo url("/"); ?>/resources/views/admin/assets/js/hoverable-collapse.js"></script>
      <script src="<?php echo url("/"); ?>/resources/views/admin/assets/js/misc.js"></script>
      <script src="<?php echo url("/"); ?>/resources/views/admin/assets/js/settings.js"></script>
      <script src="<?php echo url("/"); ?>/resources/views/admin/assets/js/todolist.js"></script>
      <!-- Replace the variables below. -->
<script>
  function onSubmit(token) {
    document.getElementById("demo-form").submit();
  }
</script>
      <!-- endinject -->
   </body>
</html>