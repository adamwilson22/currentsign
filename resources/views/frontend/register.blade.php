@include('frontend.include.header')

        <section class="signup_area signup_area_height">
            <div class="row ml-0 mr-0">
                @include('frontend.include.auth_sidebar', [
                    'variant' => 'signup',
                    'authHeading' => 'Create your account',
                    'authSub' => 'Join Current Sign to sign and manage documents securely.',
                ])
                <div class="sign_right signup_right">
                    <div class="sign_inner signup_inner">
                        <div class="text-center">
                            <h3>Create your Account</h3>
                            <p>Already have an account? <a href="{{ url('/login') }}">Sign in</a></p>
                            @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
  <!--<a href="#" class="btn-google"><img src="img/signup/gmail.png" alt=""><span class="btn-text">Sign up with Google</span></a>-->
                        </div>
                        <div class="divider">
                            <span class="or-text">or</span>
                        </div>
                        <form action="{{ url('/register-user') }}" method="POST"  class="row login_form">
                            @csrf
                            <div class="col-sm-12 form-group">
                                <div class="small_text">Full name</div>
                                <input type="text" class="form-control" name="full_name" id="name" placeholder="Muhammad">
                            </div>
                            <div class="col-lg-12 form-group">
                                <div class="small_text">Your email</div>
                                <input type="email" class="form-control" name="email" id="email" placeholder="info@KbDoc.com">
                            </div>
                            <div class="col-lg-12 form-group">
                                <div class="small_text">Password</div>
                                <input id="signup-password"  name="password" type="password" class="form-control" placeholder="Password " autocomplete="off">
                            </div>
                            <div class="col-lg-12 form-group">
                                <div class="small_text">Confirm password</div>
                                <input id="confirm-password" name="confirm_password"  type="password" class="form-control" placeholder="Confirm password required" autocomplete="off">
                            </div>
                            <div class="col-lg-12 form-group">
                                <div class="check_box">
                                    <input type="checkbox" value="None" id="squared2" >
                                    <label class="l_text" for="squared2">I accept the <span>politic of confidentiality</span></label>
                                </div>
                            </div>
                            <div class="col-lg-12 text-center">
                                <button type="submit" class="btn action_btn thm_btn">Create an account</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>


<!-- footer area -->
@include('frontend.include.footer')
</body>


</html>