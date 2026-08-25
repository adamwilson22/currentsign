@include('frontend.include.header')

<section class="cs-auth-page" aria-labelledby="register-title">
    <div class="cs-auth-page__noise" aria-hidden="true"></div>
    <div class="container">
        <div class="cs-auth-wrap cs-fade-in">
            <aside class="cs-auth-side">
                <span class="cs-badge cs-badge--gold">Create your account</span>
                <h1>Start signing with a premium, secure workflow.</h1>
                <p>Join Current Sign to send documents, collect signatures, and keep every agreement compliant and protected.</p>
                <ul>
                    <li>Free plan available instantly</li>
                    <li>Bank-grade encrypted storage</li>
                    <li>Perfect for teams and businesses</li>
                </ul>
            </aside>

            <div class="cs-auth-card">
                <h2 id="register-title">Create your account</h2>
                <p class="cs-auth-card__sub">Already have an account? <a href="{{ url('/login') }}">Sign in</a></p>

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

                <form action="{{ url('/register-user') }}" method="POST" class="cs-auth-form">
                    @csrf
                    <div class="cs-field">
                        <label for="name">Full name</label>
                        <input type="text" class="form-control" name="full_name" id="name" placeholder="John Smith" value="{{ old('full_name') }}" autocomplete="name">
                    </div>
                    <div class="cs-field">
                        <label for="email">Your email</label>
                        <input type="email" class="form-control" name="email" id="email" placeholder="john.smith@company.com" value="{{ old('email') }}" autocomplete="email">
                    </div>
                    <div class="cs-field">
                        <label for="signup-password">Password</label>
                        <input id="signup-password" name="password" type="password" class="form-control" placeholder="Password" autocomplete="off">
                    </div>
                    <div class="cs-field">
                        <label for="confirm-password">Confirm password</label>
                        <input id="confirm-password" name="confirm_password" type="password" class="form-control" placeholder="Confirm password required" autocomplete="off">
                    </div>
                    <div class="cs-field">
                        <label class="cs-auth-check">
                            <input type="checkbox" value="1" id="squared2">
                            <span>I accept the policy of confidentiality</span>
                        </label>
                    </div>
                    <button type="submit" class="cs-btn cs-btn--primary cs-auth-submit">Create an account</button>
                </form>
            </div>
        </div>
    </div>
</section>


<!-- footer area -->
@include('frontend.include.footer')
</body>


</html>