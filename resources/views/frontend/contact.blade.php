@include('frontend.include.header')
<section class="cs-contact-page" aria-labelledby="contact-title">
    <div class="cs-contact-page__noise" aria-hidden="true"></div>
    <div class="container">
        <div class="cs-contact-page__header cs-fade-in">
            <span class="cs-badge cs-badge--gold">Contact Current Sign</span>
            <h1 id="contact-title" class="cs-contact-page__title">Need help? We are ready to assist.</h1>
            <p class="cs-contact-page__sub">
                Reach our team by email, phone, or live chat. We usually respond within one business day.
            </p>
        </div>

        <div class="cs-contact-cards cs-fade-in cs-fade-in--delay">
            <article class="cs-contact-card">
                <div class="cs-contact-card__icon" aria-hidden="true">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="5" width="18" height="14" rx="2"></rect>
                        <polyline points="3 7 12 13 21 7"></polyline>
                    </svg>
                </div>
                <h3>Email</h3>
                <p>support@currentsign.com</p>
            </article>

            <article class="cs-contact-card">
                <div class="cs-contact-card__icon" aria-hidden="true">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M22 16.92V21a2 2 0 0 1-2.18 2 19.86 19.86 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.86 19.86 0 0 1 2.11 5.18 2 2 0 0 1 4.1 3h4.09a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L9.1 11a16 16 0 0 0 6 6l1.36-1.35a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                    </svg>
                </div>
                <h3>Phone</h3>
                <p>1-800-123-CURRENT (1-800-123-287738)</p>
            </article>

            <article class="cs-contact-card">
                <div class="cs-contact-card__icon" aria-hidden="true">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                    </svg>
                </div>
                <h3>Live Chat</h3>
                <p>Available during business hours (9 AM to 5 PM ET).</p>
            </article>
        </div>

        <div class="cs-contact-form-wrap cs-fade-in cs-fade-in--delay2">
            <div class="cs-contact-form-wrap__head">
                <h2>Let's start the conversation</h2>
                <p>Send us your details and our team will get back to you shortly.</p>
            </div>

            @if(session('success'))
                <div class="alert alert-success cs-contact-alert">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ url('contact-submit') }}" method="POST" class="cs-contact-form">
                @csrf
                <div class="cs-contact-form__grid">
                    <div class="cs-field">
                        <label for="name">Full name</label>
                        <input type="text" class="form-control" name="name" id="name" placeholder="Muhammad Osama">
                    </div>
                    <div class="cs-field">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" name="email" id="email" placeholder="muhammad.osama@company.com">
                    </div>
                    <div class="cs-field">
                        <label for="phone">Phone no</label>
                        <input type="tel" class="form-control" name="phone" id="phone" placeholder="+1 234 567 890">
                    </div>
                    <div class="cs-field cs-field--full">
                        <label for="message">Message</label>
                        <textarea class="form-control" name="message" id="message" placeholder="Tell us how we can help..."></textarea>
                    </div>
                </div>
                <button type="submit" class="cs-btn cs-btn--primary cs-contact-form__submit">Send Message</button>
            </form>
        </div>
    </div>
</section>
        
@include('frontend.include.footer')
</body>


</html>