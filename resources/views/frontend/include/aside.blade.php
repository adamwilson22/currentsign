<aside class="cs-app-sidebar">
    <div class="cs-app-sidebar__head">Workspace</div>
    <nav class="cs-app-nav" aria-label="User navigation">
        <a href="{{ url('/user/dashboard') }}" class="cs-app-nav__link {{ ($menu ?? '') === 'dashboard' ? 'is-active' : '' }}">
            <i class="fa fa-home" aria-hidden="true"></i>
            Dashboard
        </a>
        <a href="{{ url('/user/documents') }}" class="cs-app-nav__link {{ ($menu ?? '') === 'documents' ? 'is-active' : '' }}">
            <i class="fa fa-file" aria-hidden="true"></i>
            Documents
        </a>
        <a href="{{ url('/user/notes') }}" class="cs-app-nav__link {{ ($menu ?? '') === 'notes' ? 'is-active' : '' }}">
            <i class="fa fa-edit" aria-hidden="true"></i>
            Notes
        </a>
        <a href="{{ url('/profile') }}" class="cs-app-nav__link {{ ($menu ?? '') === 'profile' ? 'is-active' : '' }}">
            <i class="fa fa-user" aria-hidden="true"></i>
            Profile
        </a>
        <a href="{{ url('/user/notifications') }}" class="cs-app-nav__link {{ ($menu ?? '') === 'notifications' ? 'is-active' : '' }}">
            <i class="fa fa-bell" aria-hidden="true"></i>
            Notifications
        </a>
        <a href="{{ url('/logout') }}" class="cs-app-nav__link">
            <i class="fa fa-sign-out" aria-hidden="true"></i>
            Log Out
        </a>
    </nav>
</aside>