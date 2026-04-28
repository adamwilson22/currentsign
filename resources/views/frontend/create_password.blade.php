@include('frontend.include.header')

<section class="cs-auth-page" aria-labelledby="new-password-title">
    <div class="cs-auth-page__noise" aria-hidden="true"></div>
    <div class="container">
        <div class="cs-auth-wrap cs-fade-in">
            <aside class="cs-auth-side">
                <span class="cs-badge cs-badge--gold">New password</span>
                <h1>Set a strong password for your account.</h1>
                <p>Your new password helps keep your documents and signatures secure.</p>
                <ul>
                    <li>Use at least 8 characters</li>
                    <li>Combine letters, numbers, and symbols</li>
                    <li>Avoid reused passwords</li>
                </ul>
            </aside>

            <div class="cs-auth-card">
                <h2 id="new-password-title">Change Password</h2>
                <p class="cs-auth-card__sub">Create a password you can remember and keep private.</p>

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

                <form action="{{ url('/set-password') }}" method="POST" class="cs-auth-form">
                    @csrf
                    <input type="hidden" name="email" value="{{ Session::get('otp_email') }}">
                    <div class="cs-field">
                        <label for="password">New Password</label>
                        <input type="password" id="password" name="password" class="form-control" value="{{ old('password') }}" placeholder="New Password" required>
                    </div>
                    <div class="cs-field">
                        <label for="password_confirmation">Confirm Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" value="{{ old('password_confirmation') }}" placeholder="Confirm Password" required>
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