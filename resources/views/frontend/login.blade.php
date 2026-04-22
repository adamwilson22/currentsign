@include('frontend.include.header')

        <section class="signup_area signup_area_height">
            <div class="row ml-0 mr-0">
                @include('frontend.include.auth_sidebar', [
                    'variant' => 'signin',
                    'authHeading' => 'Welcome back',
                    'authSub' => 'Sign in to send, sign, and manage your documents.',
                ])
                <div class="sign_right signup_right">
                    <div class="sign_inner signup_inner">
                        <div class="text-center">
                            <h3>Sign in</h3>
                            <p>Don’t have an account yet? <a href="{{ url('/register') }}">Sign up here</a></p>
                          <!--  <a href="#" class="btn-google"><img src="img/signup/gmail.png" alt=""><span class="btn-text">Sign in with Gmail</span></a>-->
                        </div>
                        <div class="divider">
                            <span class="or-text">or</span>
                        </div>
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

                        <form action="{{ url('/login') }}" method="POST"  class="row login_form" >
                            @csrf
                            <div class="col-lg-12 form-group">
                                <div class="small_text">Your email</div>
                                <input type="email" class="form-control" name="email"  id="email"   placeholder="info@KbDoc.com">
                            </div>
                            <div class="col-lg-12 form-group">
                                <div class="small_text">Password</div>
                                <div class="confirm_password">
                                    <input id="login-password" name="password" type="password" class="form-control" placeholder="6+ characters required" autocomplete="current-password">
                                    <a href="{{ url('/forget') }}" class="forget_btn">Forgotten password?</a>
                                </div>
                            </div>

                            <div class="col-lg-12 text-center">
                                <button type="submit" class="btn action_btn thm_btn">Sign in</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
@include('frontend.include.footer')
</body>


</html>