@php
    $variant = $variant ?? 'signin';
    $leftTheme = $variant === 'signup' ? 'signup_left' : 'signin_left';
@endphp
<div class="sign_left {{ $leftTheme }}">
    <div class="middle">
        <h2>{{ $authHeading }}</h2>
        <p class="mb-0" style="color: rgba(255,255,255,0.9); font-size: 16px; line-height: 1.5;">{{ $authSub }}</p>
    </div>
    <div class="round"></div>
</div>
