@include('frontend.include.header')

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
        </div>

        <div class="cs-pricing-grid cs-fade-in cs-fade-in--delay">
            <article class="cs-price-card">
                <div class="cs-price-card__top">
                    <h3 class="cs-price-card__name">Basic</h3>
                    <p class="cs-price-card__price">
                        <span class="cs-price-card__currency">$</span>15
                        <span class="cs-price-card__meta">/ month</span>
                    </p>
                </div>
                <ul class="cs-price-card__features">
                    <li>10 documents per month</li>
                    <li>Core e-signing functionality</li>
                    <li>Email notifications and automated reminders</li>
                    <li>Signature tracking</li>
                </ul>
                <a href="{{ url('/login') }}" class="cs-btn cs-btn--ghost-white cs-price-card__cta">Upgrade now</a>
            </article>

            <article class="cs-price-card cs-price-card--featured">
                <span class="cs-price-card__tag">Most popular</span>
                <div class="cs-price-card__top">
                    <h3 class="cs-price-card__name">Standard</h3>
                    <p class="cs-price-card__price">
                        <span class="cs-price-card__currency">$</span>49
                        <span class="cs-price-card__meta">/ month</span>
                    </p>
                </div>
                <ul class="cs-price-card__features">
                    <li>50 documents per month</li>
                    <li>All Basic features</li>
                    <li>Bulk-send capability (up to 20 recipients per batch)</li>
                    <li>Team collaboration (up to 3 users)</li>
                </ul>
                <a href="{{ url('/login') }}" class="cs-btn cs-btn--primary cs-price-card__cta">Upgrade now</a>
            </article>

            <article class="cs-price-card cs-price-card--premium">
                <div class="cs-price-card__top">
                    <h3 class="cs-price-card__name">Premium</h3>
                    <p class="cs-price-card__price">
                        <span class="cs-price-card__currency">$</span>99
                        <span class="cs-price-card__meta">/ month</span>
                    </p>
                </div>
                <ul class="cs-price-card__features">
                    <li>Unlimited documents per month</li>
                    <li>All Standard features</li>
                    <li>Advanced templates and workflow automation</li>
                    <li>API access for custom integrations</li>
                    <li>Expanded team accounts (up to 10 users)</li>
                    <li>Priority support and dedicated account manager</li>
                </ul>
                <a href="{{ url('/login') }}" class="cs-btn cs-btn--primary cs-price-card__cta">Upgrade now</a>
            </article>
        </div>
    </div>
</section>


@include('frontend.include.footer')
</body>


</html>