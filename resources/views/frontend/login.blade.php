@include('frontend.include.header')

<section class="cs-auth-page" aria-labelledby="login-title">
    <div class="cs-auth-page__noise" aria-hidden="true"></div>
    <div class="container">
        <div class="cs-auth-wrap cs-fade-in">
            <aside class="cs-auth-side">
                <span class="cs-badge cs-badge--gold">Welcome back</span>
                <h1>Securely sign and manage documents in minutes.</h1>
                <p>Access your dashboard to send files, track signers, and keep every agreement encrypted end-to-end.</p>
                <ul>
                    <li>Legally binding e-signatures</li>
                    <li>Audit trail on every action</li>
                    <li>Fast workflows for teams</li>
                </ul>
            </aside>

            <div class="cs-auth-card">
                <h2 id="login-title">Sign in</h2>
                <p class="cs-auth-card__sub">Don’t have an account yet? <a href="{{ url('/register') }}">Sign up here</a></p>

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

                <form action="{{ url('/login') }}" method="POST" class="cs-auth-form">
                    @csrf
                    <div class="cs-field">
                        <label for="email">Your email</label>
                        <input type="email" class="form-control" name="email" id="email" placeholder="info@company.com">
                    </div>
                    <div class="cs-field">
                        <div class="cs-field__row">
                            <label for="login-password">Password</label>
                            <a href="{{ url('/forget') }}" class="cs-auth-link">Forgotten password?</a>
                        </div>
                        <input id="login-password" name="password" type="password" class="form-control" placeholder="6+ characters required" autocomplete="current-password">
                    </div>
                    <button type="submit" class="cs-btn cs-btn--primary cs-auth-submit">Sign in</button>
                </form>
            </div>
        </div>
    </div>
</section>
@include('frontend.include.footer')
</body>


</html>