@include('frontend.include.header')

        <section class="signup_area signup_area_height">
            <div class="row ml-0 mr-0">
                @include('frontend.include.auth_sidebar', [
                    'variant' => 'signin',
                    'authHeading' => 'New password',
                    'authSub' => 'Choose a strong password for your account.',
                ])
                <div class="sign_right signup_right">
                    <div class="sign_inner signup_inner">
                        <div class="text-center">
                            <h3>Change Password</h3>
                           
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

                        <form action="{{ url('/set-password') }}" method="POST"  class="row login_form" >
                            @csrf
                            <div class="col-lg-12 form-group">
                                <div class="small_text">New Password</div>
                                <input type="hidden" name="email" value="{{ Session::get('otp_email') }}">
                                <input type="password" name="password" class="form-control" value="{{ old('password') }}" placeholder="New Password" required>
                            </div>
                            <div class="col-lg-12 form-group">
                                <div class="small_text">Confirm password</div>
                                 <input type="password" name="password_confirmation" class="form-control" value="{{ old('password_confirmation') }}" placeholder="Confirm Password" required>
                            </div>

                            <div class="col-lg-12 text-center">
                                <button type="submit" class="btn action_btn thm_btn">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
@include('frontend.include.footer')
</body>


</html>