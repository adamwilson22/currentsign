@include('frontend.include.header')

        <section class="signup_area signup_area_height">
            <div class="row ml-0 mr-0">
                @include('frontend.include.auth_sidebar', [
                    'variant' => 'signin',
                    'authHeading' => 'Verify OTP',
                    'authSub' => 'Enter the code we sent to your email.',
                ])
                <div class="sign_right signup_right">
                    <div class="sign_inner signup_inner">
                        <div class="text-center">
                            <h3>Verify Otp</h3>
                           
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

                        <form action="{{ url('/verify-otp') }}" method="POST"  class="row login_form" >
                            @csrf
                            <div class="col-lg-12 form-group">
                                <div class="small_text">Enter Otp</div>
                                <input type="hidden" class="form-control" name="email" value="{{ request('email') }}"  id="email" >
                                <input type="number" class="form-control" name="otp" >
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