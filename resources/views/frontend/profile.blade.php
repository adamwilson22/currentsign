@include('frontend.include.header')
<section class="cs-app-page">
    <div class="container">
        <div class="cs-app-shell">
            @include('frontend.include.aside')
            <div class="cs-app-main">
                <div class="cs-app-head">
                    <h1>Profile</h1>
                    <p>Manage your personal info and security settings.</p>
                </div>

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

                <div class="cs-app-card">
                    <div class="cs-app-card__head">
                        <h3>Profile Info</h3>
                    </div>
                    <form action="{{ url('/user/update-profile') }}" method="POST" class="cs-app-form">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="cs-field">
                                    <label>Full name</label>
                                    <input class="form-control" name="full_name" placeholder="Full name" type="text" value="{{ Auth::user()->full_name }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="cs-field">
                                    <label>E-mail</label>
                                    <input class="form-control" name="email" placeholder="E-mail" type="text" value="{{ Auth::user()->email }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="cs-field">
                                    <label>Phone</label>
                                    <input class="form-control" name="mobile_number" placeholder="Phone" type="text" value="{{ Auth::user()->mobile_number }}">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="cs-field">
                                    <label>Address</label>
                                    <input class="form-control" name="address" placeholder="Address" type="text" value="{{ Auth::user()->address }}">
                                </div>
                            </div>
                        </div>
                        <button class="cs-btn cs-btn--primary" type="submit">Save Changes</button>
                    </form>
                </div>

                <div class="cs-app-card">
                    <div class="cs-app-card__head">
                        <h3>Password Settings</h3>
                    </div>
                    <form action="{{ url('/user/change-password') }}" method="POST" class="cs-app-form">
                        @csrf
                        <div class="cs-field">
                            <label>Old Password</label>
                            <input class="form-control" name="old_password" type="password">
                        </div>
                        <div class="cs-field">
                            <label>New Password</label>
                            <input class="form-control" name="new_password" type="password" required>
                        </div>
                        <div class="cs-field">
                            <label>Repeat New Password</label>
                            <input class="form-control" name="confirm_password" type="password" required>
                        </div>
                        <button class="cs-btn cs-btn--primary" type="submit">Change Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@include('frontend.include.footer')
</body>


</html>