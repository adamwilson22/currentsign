<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="{{ asset('assets_web/img/CS-2-TBG.png') }}" type="image/png">
    <title>Current Sign — Sign Documents in Seconds</title>
    <meta name="description" content="Legally binding e-signatures for businesses &amp; individuals. Free to start, no printer needed.">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Google Fonts: Inter + DM Serif Display for premium headings -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet">

    <!-- Bootstrap 4 -->
    <link rel="stylesheet" href="{{ asset('assets_web/assets/bootstrap/css/bootstrap.min.css') }}">
    <!-- Icon fonts -->
    <link rel="stylesheet" href="{{ asset('assets_web/assets/elagent-icon/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets_web/assets/font-awesome/css/all.css') }}">
    <!-- Original theme (kept for inner pages) -->
    <link rel="stylesheet" href="{{ asset('assets_web/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets_web/css/responsive.css') }}">
    <!-- Current Sign design system -->
    <link rel="stylesheet" href="{{ asset('assets_web/css/cs-home.css') }}?v={{ filemtime(public_path('assets_web/css/cs-home.css')) }}">
</head>

<body>

<!-- ─── PREMIUM NAVBAR ─────────────────────────────────────────── -->
<header class="cs-nav" id="cs-nav">
    <!-- Top accent line -->
    <div class="cs-nav__accent-line"></div>

    <div class="container cs-nav__inner">

        <!-- Brand — uses real logo image; dark nav background matches logo bg perfectly -->
        <a class="cs-nav__brand" href="{{ url('/') }}" aria-label="Current Sign home">
            <img
                src="{{ asset('assets_web/img/CS-2-TBG.png') }}"
                alt="Current Sign Logo"
                class="cs-nav__logo-img"
                width="150"
                height="50"
            >
        </a>

        <!-- Desktop links -->
        <nav class="cs-nav__links" aria-label="Main navigation">
            <a href="{{ url('/') }}"          class="cs-nav__link {{ request()->is('/') ? 'active' : '' }}">Home</a>
            <a href="{{ url('/#cs-about') }}" class="cs-nav__link">About</a>
            <a href="{{ url('/pricing') }}"   class="cs-nav__link {{ request()->is('pricing') ? 'active' : '' }}">Pricing</a>
            <a href="{{ url('/contact') }}"   class="cs-nav__link {{ request()->is('contact') ? 'active' : '' }}">Contact</a>
        </nav>

        <!-- CTA buttons -->
        <div class="cs-nav__cta">
            @guest
                <a href="{{ url('/login') }}"    class="cs-btn cs-btn--nav-outline">Log In</a>
                <a href="{{ url('/register') }}" class="cs-btn cs-btn--nav-primary">
                    Start Free
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                </a>
            @endguest
            @auth
                <a href="{{ url('user/dashboard') }}" class="cs-btn cs-btn--nav-primary">
                    Dashboard
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                </a>
            @endauth
        </div>

        <!-- Mobile hamburger -->
        <button class="cs-nav__hamburger" id="cs-hamburger" aria-label="Open menu" aria-expanded="false">
            <span></span><span></span><span></span>
        </button>
    </div>

    <!-- Mobile drawer -->
    <div class="cs-nav__drawer" id="cs-drawer" aria-hidden="true">
        <div class="cs-drawer__header">
            <img src="{{ asset('assets_web/img/CS-2-TBG.png') }}" alt="Current Sign Logo" class="cs-drawer__logo">
            <button class="cs-drawer__close" id="cs-drawer-close" aria-label="Close menu">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <nav aria-label="Mobile navigation">
            <a href="{{ url('/') }}"          class="cs-drawer__link">Home</a>
            <a href="{{ url('/#cs-about') }}" class="cs-drawer__link">About</a>
            <a href="{{ url('/pricing') }}"   class="cs-drawer__link">Pricing</a>
            <a href="{{ url('/contact') }}"   class="cs-drawer__link">Contact</a>
            @guest
                <a href="{{ url('/login') }}" class="cs-drawer__link cs-drawer__link--login">Login</a>
                <a href="{{ url('/register') }}" class="cs-drawer__link">Register</a>
            @endguest
            @auth
                <a href="{{ url('user/dashboard') }}" class="cs-drawer__link">Dashboard</a>
            @endauth
        </nav>
        <div class="cs-drawer__cta">
            @guest
                <a href="{{ url('/login') }}"    class="cs-btn cs-btn--outline w-100 mb-2">Log In</a>
                <a href="{{ url('/register') }}" class="cs-btn cs-btn--primary w-100">Start Free →</a>
            @endguest
            @auth
                <a href="{{ url('user/dashboard') }}" class="cs-btn cs-btn--primary w-100">Dashboard →</a>
            @endauth
        </div>
    </div>
</header>
<div class="cs-nav__overlay" id="cs-overlay"></div>

<div class="cs-page-body">
