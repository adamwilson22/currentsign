@include('frontend.include.header')

@php
    $currentPlan = null;
    $planActive = false;
    if (auth()->check()) {
        $u = auth()->user();
        $status = strtolower((string) ($u->subscription_status ?? ''));
        $plan = strtolower((string) ($u->plan ?? ''));
        if (in_array($status, ['active', 'trialing'], true) && in_array($plan, ['basic', 'standard', 'premium'], true)) {
            $currentPlan = $plan;
            $planActive = true;
        }
    }
@endphp

<section class="cs-pricing-page" aria-labelledby="pricing-title">
    <div class="cs-pricing-page__noise" aria-hidden="true"></div>
    <div class="container">
        <div class="cs-pricing-page__header cs-fade-in">
            <span class="cs-badge cs-badge--gold">Flexible plans</span>
            <h1 id="pricing-title" class="cs-pricing-page__title">
                Choose the plan that scales with your <span class="cs-text-gold">business</span>
            </h1>
            <p class="cs-pricing-page__sub">
                Start with a simple plan and move up anytime. All plans include secure cloud storage,
                legally binding signatures, and trusted delivery.
            </p>
            @if($planActive)
                <p class="cs-pricing-page__sub" style="margin-top:.75rem;">
                    Your current plan: <strong class="cs-text-gold">{{ ucfirst($currentPlan) }}</strong>
                </p>
            @endif
            @if(session()->has(\App\Services\PendingSignatureSend::SESSION_KEY))
                <p class="cs-pricing-page__sub" style="margin-top:.75rem;">
                    Your document is waiting. After checkout we will send it and bring you back automatically.
                </p>
            @endif
        </div>

        <div class="cs-pricing-grid cs-fade-in cs-fade-in--delay">
            @foreach([
                'basic' => [
                    'name' => 'Basic',
                    'price' => '15',
                    'features' => [
                        '10 documents per month',
                        'Core e-signing functionality',
                        'Email notifications and automated reminders',
                        'Signature tracking',
                    ],
                    'default_featured' => false,
                ],
                'standard' => [
                    'name' => 'Standard',
                    'price' => '49',
                    'features' => [
                        '50 documents per month',
                        'All Basic features',
                        'Bulk-send capability (up to 20 recipients per batch)',
                        'Team collaboration (up to 3 users)',
                    ],
                    'default_featured' => true,
                ],
                'premium' => [
                    'name' => 'Premium',
                    'price' => '99',
                    'features' => [
                        'Unlimited documents per month',
                        'All Standard features',
                        'Advanced templates and workflow automation',
                        'API access for custom integrations',
                        'Expanded team accounts (up to 10 users)',
                        'Priority support and dedicated account manager',
                    ],
                    'default_featured' => false,
                    'premium' => true,
                ],
            ] as $planId => $planMeta)
                @php
                    $isCurrent = $currentPlan === $planId;
                    $isFeatured = $isCurrent || (! $planActive && ! empty($planMeta['default_featured']));
                    $cardClass = 'cs-price-card';
                    if ($isCurrent) {
                        $cardClass .= ' cs-price-card--current cs-price-card--featured';
                    } elseif ($isFeatured) {
                        $cardClass .= ' cs-price-card--featured';
                    }
                    if (! empty($planMeta['premium'])) {
                        $cardClass .= ' cs-price-card--premium';
                    }
                @endphp
                <article class="{{ $cardClass }}">
                    @if($isCurrent)
                        <span class="cs-price-card__tag">Current plan</span>
                    @elseif(! $planActive && ! empty($planMeta['default_featured']))
                        <span class="cs-price-card__tag">Most popular</span>
                    @endif
                    <div class="cs-price-card__top">
                        <h3 class="cs-price-card__name">{{ $planMeta['name'] }}</h3>
                        <p class="cs-price-card__price">
                            <span class="cs-price-card__currency">$</span>{{ $planMeta['price'] }}
                            <span class="cs-price-card__meta">/ month</span>
                        </p>
                    </div>
                    <ul class="cs-price-card__features">
                        @foreach($planMeta['features'] as $feature)
                            <li>{{ $feature }}</li>
                        @endforeach
                    </ul>
                    @if($isCurrent)
                        <a href="{{ url('/billing/portal') }}" class="cs-btn cs-btn--primary cs-price-card__cta">Manage plan</a>
                    @else
                        <a href="{{ url('/billing/checkout/' . $planId) }}" class="cs-btn {{ $isFeatured ? 'cs-btn--primary' : 'cs-btn--ghost-white' }} cs-price-card__cta">
                            {{ $planActive ? 'Switch to ' . $planMeta['name'] : 'Upgrade now' }}
                        </a>
                    @endif
                </article>
            @endforeach
        </div>

        @if(session('error'))
            <p class="cs-pricing-page__sub" style="text-align:center;color:#c0392b;margin-top:1rem;">{{ session('error') }}</p>
        @endif
        @if(request('canceled'))
            <p class="cs-pricing-page__sub" style="text-align:center;margin-top:1rem;">Checkout canceled. You can choose a plan anytime.</p>
        @endif
        @auth
        <p class="cs-pricing-page__sub" style="text-align:center;margin-top:1.5rem;">
            Already subscribed?
            <a href="{{ url('/billing/portal') }}" class="cs-text-gold">Manage subscription</a>
        </p>
        @endauth
    </div>
</section>


@include('frontend.include.footer')
</body>


</html>
