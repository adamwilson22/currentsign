@include('frontend.include.header')
<section class="cs-app-page">
    <div class="container">
        <div class="cs-app-shell">
            @include('frontend.include.aside')
            <div class="cs-app-main">
                <div class="cs-app-head">
                    <h1>Notifications</h1>
                    <p>Recent activity and updates from your account.</p>
                </div>

                @if($errors->any())
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
                        <h3>Latest Notifications</h3>
                    </div>

                    @forelse($notifications as $notify)
                        <div class="cs-app-notification">
                            <div class="cs-app-notification__icon">
                                <i class="fa fa-bell" aria-hidden="true"></i>
                            </div>
                            <div>
                                <strong>{{ $notify->notification_text }}</strong>
                                <p>{{ $notify->notification_message }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="cs-app-empty mb-0">No notifications yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</section>

@include('frontend.include.footer')
</body>



</html>