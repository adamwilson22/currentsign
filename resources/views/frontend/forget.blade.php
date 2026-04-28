@include('frontend.include.header')

<section class="cs-auth-page" aria-labelledby="forget-title">
    <div class="cs-auth-page__noise" aria-hidden="true"></div>
    <div class="container">
        <div class="cs-auth-wrap cs-fade-in">
            <aside class="cs-auth-side">
                <span class="cs-badge cs-badge--gold">Reset password</span>
                <h1>Recover your account quickly and securely.</h1>
                <p>Enter your registered email and we will send you a one-time verification code to continue.</p>
                <ul>
                    <li>Fast OTP-based recovery</li>
                    <li>Secure verification flow</li>
                    <li>No data loss or account reset risk</li>
                </ul>
            </aside>

            <div class="cs-auth-card">
                <h2 id="forget-title">Forgot Password</h2>
                <p class="cs-auth-card__sub">Remembered your credentials? <a href="{{ url('/login') }}">Sign in</a></p>

                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ url('/sendotp') }}" method="POST" class="cs-auth-form">
                    @csrf
                    <div class="cs-field">
                        <label for="email">Your email</label>
                        <input type="email" class="form-control" name="email" id="email" placeholder="info@company.com">
                    </div>
                    <button type="submit" class="cs-btn cs-btn--primary cs-auth-submit">Send OTP</button>
                </form>
            </div>
        </div>
    </div>
</section>
@include('frontend.include.footer')
</body>


</html>