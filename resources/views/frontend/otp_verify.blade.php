@include('frontend.include.header')

<section class="cs-auth-page" aria-labelledby="otp-title">
    <div class="cs-auth-page__noise" aria-hidden="true"></div>
    <div class="container">
        <div class="cs-auth-wrap cs-fade-in">
            <aside class="cs-auth-side">
                <span class="cs-badge cs-badge--gold">Verify OTP</span>
                <h1>Confirm your one-time verification code.</h1>
                <p>Enter the OTP sent to your email to continue securely with account recovery.</p>
                <ul>
                    <li>Quick email verification</li>
                    <li>Secure one-time code flow</li>
                    <li>Protected password reset</li>
                </ul>
            </aside>

            <div class="cs-auth-card">
                <h2 id="otp-title">Verify OTP</h2>
                <p class="cs-auth-card__sub">Did not receive the code? Go back and request a new OTP.</p>

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
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

                <form action="{{ url('/verify-otp') }}" method="POST" class="cs-auth-form">
                    @csrf
                    <input type="hidden" name="email" value="{{ request('email') }}" id="email">
                    <div class="cs-field">
                        <label for="otp">Enter OTP</label>
                        <input type="number" class="form-control" id="otp" name="otp">
                    </div>
                    <button type="submit" class="cs-btn cs-btn--primary cs-auth-submit">Submit</button>
                </form>
            </div>
        </div>
    </div>
</section>
@include('frontend.include.footer')
</body>


</html>