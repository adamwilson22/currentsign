@include('frontend.include.header')

        <section class="signup_area signup_area_height">
            <div class="row ml-0 mr-0">
                @include('frontend.include.auth_sidebar', [
                    'variant' => 'signin',
                    'authHeading' => 'Reset password',
                    'authSub' => 'We will email you a one-time code to sign back in.',
                ])
                <div class="sign_right signup_right">
                    <div class="sign_inner signup_inner">
                        <div class="text-center">
                            <h3>Forget Password</h3>
                           
                        </div>
                        <div class="divider">
                           
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

                        <form action="{{ url('/sendotp') }}" method="POST"  class="row login_form" >
                            @csrf
                            <div class="col-lg-12 form-group">
                                <div class="small_text">Your email</div>
                                <input type="email" class="form-control" name="email"  id="email" placeholder="info@KbDoc.com">
                            </div>
                           

                            <div class="col-lg-12 text-center">
                                <button type="submit" class="btn action_btn thm_btn">Send Otp</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
@include('frontend.include.footer')
</body>


</html>