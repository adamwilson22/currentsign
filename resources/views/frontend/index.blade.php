@include('frontend.include.header')

{{-- ═══════════════════════════════════════════════════════════
     1. HERO
═══════════════════════════════════════════════════════════ --}}
<section class="cs-hero" id="cs-hero">
    <div class="cs-hero__noise" aria-hidden="true"></div>
    <div class="container cs-hero__inner">

        <!-- Left: Copy -->
        <div class="cs-hero__copy cs-fade-in">
            <div class="cs-hero__eyebrow">
                The modern e-signature platform
            </div>

            <h1 class="cs-hero__h1">
                Sign Documentations<br>
                <span class="cs-hero__serif">in Secondsssssss.</span><br>
                <span class="cs-hero__highlight">No Printer Needed.</span>
            </h1>

            <p class="cs-hero__sub">
                Legally binding e-signatures for businesses &amp; individuals.<br>
                Free to start — no credit card required.
            </p>

            <!-- CTAs -->
            <div class="cs-hero__ctas">
                <a href="{{ url('/register') }}" class="cs-btn cs-btn--primary cs-btn--lg">
                    Start Signing Free
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                </a>
                <a href="#cs-how" class="cs-btn cs-btn--ghost-white cs-btn--lg">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M8 5v14l11-7z"/></svg>
                    See How It Works
                </a>
            </div>

            <!-- Trust row -->
            <div class="cs-trust-row">
                <span class="cs-trust-item">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    256-bit Encrypted
                </span>
                <span class="cs-trust-sep">·</span>
                <span class="cs-trust-item">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                    Legally Binding
                </span>
                <span class="cs-trust-sep">·</span>
                <span class="cs-trust-item">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="5" y="2" width="14" height="20" rx="2"/><line x1="12" y1="18" x2="12.01" y2="18"/></svg>
                    Works on Any Device
                </span>
            </div>
        </div>

        <!-- Right: Product mockup -->
        <div class="cs-hero__visual cs-fade-in cs-fade-in--delay">
            <div class="cs-doc-card" aria-label="Document signing mockup">
                <!-- doc header -->
                <div class="cs-doc-card__header">
                    <div class="cs-doc-card__dots">
                        <span></span><span></span><span></span>
                    </div>
                    <span class="cs-doc-card__title">Service Agreement.pdf</span>
                </div>
                <!-- doc body -->
                <div class="cs-doc-card__body">
                    <div class="cs-doc-card__line cs-doc-card__line--wide"></div>
                    <div class="cs-doc-card__line"></div>
                    <div class="cs-doc-card__line cs-doc-card__line--med"></div>
                    <div class="cs-doc-card__line cs-doc-card__line--wide mt-2"></div>
                    <div class="cs-doc-card__line"></div>

                    <!-- Signature field 1 -->
                    <div class="cs-sig-field cs-sig-field--signed mt-3">
                        <div class="cs-sig-field__label">
                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                            Signed by Sarah Chen
                        </div>
                        <div class="cs-sig-field__ink">Sarah Chen</div>
                        <div class="cs-sig-field__date">Apr 22, 2026 · 10:45 AM</div>
                    </div>

                    <!-- Signature field 2 -->
                    <div class="cs-sig-field cs-sig-field--pending mt-2">
                        <div class="cs-sig-field__label cs-sig-field__label--pending">
                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/></svg>
                            Awaiting: Muhammad Osama
                        </div>
                        <div class="cs-sig-field__placeholder">Click to sign here</div>
                    </div>
                </div>
                <!-- status bar -->
                <div class="cs-doc-card__footer">
                    <span class="cs-pill cs-pill--green">
                        <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                        1 / 2 Signed
                    </span>
                    <span class="cs-pill cs-pill--amber">
                        <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="10"/></svg>
                        Awaiting
                    </span>
                </div>
            </div>

            <!-- bottom-left floating badge: encryption -->
            <div class="cs-hero__float-badge" aria-hidden="true">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                <span>AES-256 Encrypted</span>
            </div>

            <!-- top-right floating badge: legally binding -->
            <div class="cs-hero__float-badge2" aria-hidden="true">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                <span>eIDAS Compliant</span>
            </div>
        </div>

    </div>
</section>

{{-- ═══════════════════════════════════════════════════════════
     2. SOCIAL PROOF / STATS  (dark — flows from hero)
═══════════════════════════════════════════════════════════ --}}
<section class="cs-stats" aria-label="Platform statistics">
    <div class="container">

        <!-- Stats grid -->
        <div class="cs-stats__grid cs-fade-in">
            <div class="cs-stat">
                <div class="cs-stat__icon" aria-hidden="true">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                </div>
                <div class="cs-stat__row">
                    <span class="cs-stat__num" data-target="10000">0</span>
                    <span class="cs-stat__plus">+</span>
                </div>
                <p class="cs-stat__label">Documents Signed</p>
            </div>
            <div class="cs-stat">
                <div class="cs-stat__icon" aria-hidden="true">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                </div>
                <div class="cs-stat__row">
                    <span class="cs-stat__num" data-target="5000">0</span>
                    <span class="cs-stat__plus">+</span>
                </div>
                <p class="cs-stat__label">Active Users</p>
            </div>
            <div class="cs-stat">
                <div class="cs-stat__icon" aria-hidden="true">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                </div>
                <div class="cs-stat__row">
                    <span class="cs-stat__num" data-target="99">0</span>
                    <span class="cs-stat__suffix">.9%</span>
                </div>
                <p class="cs-stat__label">Guaranteed Uptime</p>
            </div>
            <div class="cs-stat">
                <div class="cs-stat__icon" aria-hidden="true">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
                </div>
                <div class="cs-stat__row">
                    <span class="cs-stat__num" data-target="50">0</span>
                    <span class="cs-stat__plus">+</span>
                </div>
                <p class="cs-stat__label">Countries Served</p>
            </div>
        </div>

        <!-- Logo strip -->
        <div class="cs-stats__divider cs-fade-in" aria-hidden="true"></div>
        <p class="cs-stats__trusted cs-fade-in">Trusted by teams at</p>
        <div class="cs-logo-row cs-fade-in" aria-label="Company logos">
            <div class="cs-logo-box">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/></svg>
                <span>Acme Corp</span>
            </div>
            <div class="cs-logo-box">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                <span>NovaTech</span>
            </div>
            <div class="cs-logo-box">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
                <span>GlobalSign</span>
            </div>
            <div class="cs-logo-box">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                <span>PulseHQ</span>
            </div>
            <div class="cs-logo-box">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                <span>TeamFlow</span>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════════
     3. HOW IT WORKS
═══════════════════════════════════════════════════════════ --}}
<section class="cs-how" id="cs-how" aria-labelledby="how-title">
    <div class="container">
        <div class="cs-section-header cs-fade-in">
            <div class="cs-badge cs-badge--gold mb-3">Simple 3-step process</div>
            <h2 id="how-title" class="cs-section-title">How It Works</h2>
            <p class="cs-section-sub">Get your first document signed in under 2 minutes — no training needed.</p>
        </div>

        <!-- Steps with connector track -->
        <div class="cs-steps-wrap cs-fade-in">
            <div class="cs-steps">

                <!-- Step 1 -->
                <div class="cs-step" data-num="01">
                    <div class="cs-step__badge">01</div>
                    <div class="cs-step__icon-wrap" aria-hidden="true">
                        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                    </div>
                    <h3 class="cs-step__title">Upload Your Document</h3>
                    <p class="cs-step__desc">Drag &amp; drop any PDF or Word file directly into your dashboard. We handle the formatting.</p>
                    <a href="{{ url('/register') }}" class="cs-step__link">
                        Try it free
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                    </a>
                </div>

                <!-- connector -->
                <div class="cs-step__connector" aria-hidden="true">
                    <svg width="40" height="16" viewBox="0 0 40 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M0 8 Q10 2 20 8 Q30 14 40 8" stroke="#C5A27D" stroke-width="1.5" fill="none" stroke-dasharray="3 3"/>
                        <polyline points="34 4 40 8 34 12" stroke="#C5A27D" stroke-width="1.5" fill="none" stroke-linecap="round"/>
                    </svg>
                </div>

                <!-- Step 2 -->
                <div class="cs-step cs-step--accent" data-num="02">
                    <div class="cs-step__badge cs-step__badge--accent">02</div>
                    <div class="cs-step__icon-wrap cs-step__icon-wrap--accent" aria-hidden="true">
                        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    </div>
                    <h3 class="cs-step__title">Add Signers &amp; Fields</h3>
                    <p class="cs-step__desc">Place signature boxes, initials, and date fields precisely where each signer needs to act.</p>
                    <a href="{{ url('/register') }}" class="cs-step__link cs-step__link--accent">
                        See how
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                    </a>
                </div>

                <!-- connector -->
                <div class="cs-step__connector" aria-hidden="true">
                    <svg width="40" height="16" viewBox="0 0 40 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M0 8 Q10 2 20 8 Q30 14 40 8" stroke="#C5A27D" stroke-width="1.5" fill="none" stroke-dasharray="3 3"/>
                        <polyline points="34 4 40 8 34 12" stroke="#C5A27D" stroke-width="1.5" fill="none" stroke-linecap="round"/>
                    </svg>
                </div>

                <!-- Step 3 -->
                <div class="cs-step" data-num="03">
                    <div class="cs-step__badge">03</div>
                    <div class="cs-step__icon-wrap" aria-hidden="true">
                        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                    </div>
                    <h3 class="cs-step__title">Send &amp; Get Notified</h3>
                    <p class="cs-step__desc">Send via email and receive instant alerts the moment every party completes their signature.</p>
                    <a href="{{ url('/register') }}" class="cs-step__link">
                        Get started
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                    </a>
                </div>

            </div>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════════
     4. FEATURES (tabbed showcase)
═══════════════════════════════════════════════════════════ --}}
<section class="cs-features" id="cs-features" aria-labelledby="feat-title">
    <div class="container">
        <div class="cs-section-header cs-fade-in">
            <h2 id="feat-title" class="cs-section-title">Everything You Need to Sign &amp; Manage</h2>
            <p class="cs-section-sub">One platform. All the tools your business needs to close deals faster.</p>
        </div>

        <div class="cs-feat__layout cs-fade-in">
            <!-- Tab list -->
            <div class="cs-feat__tabs" role="tablist" aria-label="Feature tabs">
                <button class="cs-feat__tab active" role="tab" aria-selected="true"  aria-controls="feat-panel-1" id="feat-tab-1">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M12 20h9"/><path d="M16.5 3.5l4 4L7 21H3v-4L16.5 3.5z"/></svg>
                    E-Signatures
                </button>
                <button class="cs-feat__tab" role="tab" aria-selected="false" aria-controls="feat-panel-2" id="feat-tab-2">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    Secure Storage
                </button>
                <button class="cs-feat__tab" role="tab" aria-selected="false" aria-controls="feat-panel-3" id="feat-tab-3">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                    Audit Trails
                </button>
                <button class="cs-feat__tab" role="tab" aria-selected="false" aria-controls="feat-panel-4" id="feat-tab-4">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="5" y="2" width="14" height="20" rx="2"/><line x1="12" y1="18" x2="12.01" y2="18"/></svg>
                    Cross-Platform
                </button>
                <button class="cs-feat__tab" role="tab" aria-selected="false" aria-controls="feat-panel-5" id="feat-tab-5">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
                    API Integrations
                </button>
                <button class="cs-feat__tab" role="tab" aria-selected="false" aria-controls="feat-panel-6" id="feat-tab-6">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><polyline points="20 12 20 22 4 22 4 12"/><rect x="2" y="7" width="20" height="5"/><path d="M12 22V7"/><path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z"/><path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z"/></svg>
                    Free Access
                </button>
            </div>

            <!-- Panels -->
            <div class="cs-feat__panels">
                <div class="cs-feat__panel active" id="feat-panel-1" role="tabpanel" aria-labelledby="feat-tab-1">
                    <div class="cs-feat__panel-mock">
                        <div class="cs-mock-sig">
                            <div class="cs-mock-sig__line"></div>
                            <div class="cs-mock-sig__line cs-mock-sig__line--short"></div>
                            <div class="cs-mock-sig__field">
                                <span class="cs-mock-sig__cursor"></span>
                            </div>
                        </div>
                    </div>
                    <div class="cs-feat__panel-copy">
                        <h3>Electronic Signatures</h3>
                        <p>Create legally binding digital signatures on any document — contracts, NDAs, agreements, and more. Draw, type, or upload your signature in seconds.</p>
                        <a href="{{ url('/register') }}" class="cs-link-arrow">Try it free →</a>
                    </div>
                </div>
                <div class="cs-feat__panel" id="feat-panel-2" role="tabpanel" aria-labelledby="feat-tab-2">
                    <div class="cs-feat__panel-mock">
                        <div class="cs-mock-vault">
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#C5A27D" stroke-width="1.5" aria-hidden="true"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/><circle cx="12" cy="16" r="1" fill="#C5A27D"/></svg>
                            <p class="mt-2" style="font-size:13px;color:#6b7280;">AES-256 encrypted vault</p>
                        </div>
                    </div>
                    <div class="cs-feat__panel-copy">
                        <h3>Secure Storage</h3>
                        <p>All signed documents are stored in an AES-256 encrypted, cloud-based repository. Access your files anytime, from anywhere, with role-based permissions.</p>
                        <a href="{{ url('/register') }}" class="cs-link-arrow">Store docs free →</a>
                    </div>
                </div>
                <div class="cs-feat__panel" id="feat-panel-3" role="tabpanel" aria-labelledby="feat-tab-3">
                    <div class="cs-feat__panel-mock">
                        <div class="cs-mock-trail">
                            <div class="cs-trail-item"><span class="cs-trail-dot cs-trail-dot--green"></span>Opened · 10:42 AM</div>
                            <div class="cs-trail-item"><span class="cs-trail-dot cs-trail-dot--blue"></span>Viewed page 3 · 10:43 AM</div>
                            <div class="cs-trail-item"><span class="cs-trail-dot cs-trail-dot--gold"></span>Signed · 10:45 AM</div>
                        </div>
                    </div>
                    <div class="cs-feat__panel-copy">
                        <h3>Audit Trails</h3>
                        <p>Every action on a document is timestamped and logged. Get court-admissible audit trails showing who viewed, signed, and when — ensuring full compliance.</p>
                        <a href="{{ url('/register') }}" class="cs-link-arrow">See audit logs →</a>
                    </div>
                </div>
                <div class="cs-feat__panel" id="feat-panel-4" role="tabpanel" aria-labelledby="feat-tab-4">
                    <div class="cs-feat__panel-mock">
                        <div class="cs-mock-devices">
                            <svg width="42" height="42" viewBox="0 0 24 24" fill="none" stroke="#C5A27D" stroke-width="1.5" aria-hidden="true"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#0F172A" stroke-width="1.5" aria-hidden="true"><rect x="5" y="2" width="14" height="20" rx="2"/><line x1="12" y1="18" x2="12.01" y2="18"/></svg>
                        </div>
                    </div>
                    <div class="cs-feat__panel-copy">
                        <h3>Cross-Platform Access</h3>
                        <p>Sign on desktop, tablet, or mobile. Current Sign is fully responsive and works on all major browsers — no app download required.</p>
                        <a href="{{ url('/register') }}" class="cs-link-arrow">Start on any device →</a>
                    </div>
                </div>
                <div class="cs-feat__panel" id="feat-panel-5" role="tabpanel" aria-labelledby="feat-tab-5">
                    <div class="cs-feat__panel-mock">
                        <div class="cs-mock-api">
                            <code class="cs-mock-api__code">POST /api/folders/submitSignature</code>
                            <div class="cs-pill cs-pill--green mt-2">200 OK</div>
                        </div>
                    </div>
                    <div class="cs-feat__panel-copy">
                        <h3>API Integrations</h3>
                        <p>Embed e-signature workflows directly into your CRM, ERP, or custom app via our REST API. Webhooks keep your systems in sync automatically.</p>
                        <a href="{{ url('/contact') }}" class="cs-link-arrow">Talk to our team →</a>
                    </div>
                </div>
                <div class="cs-feat__panel" id="feat-panel-6" role="tabpanel" aria-labelledby="feat-tab-6">
                    <div class="cs-feat__panel-mock">
                        <div class="cs-mock-free text-center">
                            <div style="font-size:40px;font-weight:800;color:#C5A27D;">$0</div>
                            <div style="font-size:13px;color:#6b7280;">Core features, always free</div>
                        </div>
                    </div>
                    <div class="cs-feat__panel-copy">
                        <h3>Free Access</h3>
                        <p>Get started without entering a credit card. Our generous free tier includes 5 documents per month, secure storage, and full e-signature capability.</p>
                        <a href="{{ url('/register') }}" class="cs-link-arrow">Create free account →</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════════
     5. TESTIMONIALS
═══════════════════════════════════════════════════════════ --}}
<section class="cs-testimonials" id="cs-testimonials" aria-labelledby="testi-title">
    <div class="container">
        <div class="cs-section-header cs-fade-in">
            <h2 id="testi-title" class="cs-section-title">Loved by Teams Everywhere</h2>
            <p class="cs-section-sub">Real results from real customers using Current Sign every day.</p>
        </div>

        <div class="cs-carousel cs-fade-in" id="cs-carousel" aria-label="Customer testimonials">
            <!-- Card 1 -->
            <div class="cs-testi-card">
                <div class="cs-stars" aria-label="5 out of 5 stars">★★★★★</div>
                <blockquote class="cs-testi-card__quote">
                    "We cut our contract turnaround from 3 days to under 30 minutes. Current Sign is simply the fastest way to get documents signed without any fuss."
                </blockquote>
                <div class="cs-testi-card__author">
                    <div class="cs-avatar cs-avatar--teal" aria-hidden="true">SC</div>
                    <div>
                        <strong>Sarah Chen</strong>
                        <span>Head of Operations · Nextra Inc.</span>
                    </div>
                </div>
            </div>
            <!-- Card 2 -->
            <div class="cs-testi-card">
                <div class="cs-stars" aria-label="5 out of 5 stars">★★★★★</div>
                <blockquote class="cs-testi-card__quote">
                    "The audit trail feature alone has saved us countless hours in compliance reviews. It integrates seamlessly with how we already work."
                </blockquote>
                <div class="cs-testi-card__author">
                    <div class="cs-avatar cs-avatar--navy" aria-hidden="true">JM</div>
                    <div>
                        <strong>James Miller</strong>
                        <span>General Counsel · Meridian Law</span>
                    </div>
                </div>
            </div>
            <!-- Card 3 -->
            <div class="cs-testi-card">
                <div class="cs-stars" aria-label="5 out of 5 stars">★★★★★</div>
                <blockquote class="cs-testi-card__quote">
                    "As a freelancer I've tried four e-sign tools. Current Sign is the only one that's genuinely free for small volumes and still feels professional."
                </blockquote>
                <div class="cs-testi-card__author">
                    <div class="cs-avatar cs-avatar--gold" aria-hidden="true">AP</div>
                    <div>
                        <strong>Amara Patel</strong>
                        <span>Independent Consultant</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Carousel dots -->
        <div class="cs-carousel__dots" role="tablist" aria-label="Testimonial navigation">
            <button class="cs-carousel__dot active" role="tab" aria-label="Testimonial 1" data-idx="0"></button>
            <button class="cs-carousel__dot" role="tab" aria-label="Testimonial 2" data-idx="1"></button>
            <button class="cs-carousel__dot" role="tab" aria-label="Testimonial 3" data-idx="2"></button>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════════
     6. FAQ
═══════════════════════════════════════════════════════════ --}}
<section class="cs-faq" id="cs-faq" aria-labelledby="faq-title">
    <div class="container cs-faq__inner">
        <div class="cs-section-header cs-fade-in">
            <h2 id="faq-title" class="cs-section-title">Frequently Asked Questions</h2>
            <p class="cs-section-sub">Can't find what you're looking for? <a href="{{ url('/contact') }}" class="cs-link-arrow">Contact us →</a></p>
        </div>

        <div class="cs-accordion cs-fade-in" id="cs-faq-list">

            <div class="cs-acc-item" id="faq-1">
                <button class="cs-acc-trigger" aria-expanded="false" aria-controls="faq-body-1">
                    How do I start using Current Sign?
                    <svg class="cs-acc-chevron" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><polyline points="6 9 12 15 18 9"/></svg>
                </button>
                <div class="cs-acc-body" id="faq-body-1" hidden>
                    Create a free account, upload your document, add signer details, place signature fields, and hit send. Your first document can be signed in under 2 minutes.
                </div>
            </div>

            <div class="cs-acc-item" id="faq-2">
                <button class="cs-acc-trigger" aria-expanded="false" aria-controls="faq-body-2">
                    Are digital signatures legally binding?
                    <svg class="cs-acc-chevron" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><polyline points="6 9 12 15 18 9"/></svg>
                </button>
                <div class="cs-acc-body" id="faq-body-2" hidden>
                    Yes. Digital signatures created through Current Sign comply with the ESIGN Act (US), eIDAS (EU), and equivalent laws in 50+ countries. We recommend consulting legal counsel for jurisdiction-specific advice.
                </div>
            </div>

            <div class="cs-acc-item" id="faq-3">
                <button class="cs-acc-trigger" aria-expanded="false" aria-controls="faq-body-3">
                    How secure is my data on Current Sign?
                    <svg class="cs-acc-chevron" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><polyline points="6 9 12 15 18 9"/></svg>
                </button>
                <div class="cs-acc-body" id="faq-body-3" hidden>
                    We use AES-256 encryption at rest and TLS 1.3 in transit. All document activity is logged in an immutable audit trail. We never share your data with third parties.
                </div>
            </div>

            <div class="cs-acc-item" id="faq-4">
                <button class="cs-acc-trigger" aria-expanded="false" aria-controls="faq-body-4">
                    Can I integrate Current Sign with other business systems?
                    <svg class="cs-acc-chevron" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><polyline points="6 9 12 15 18 9"/></svg>
                </button>
                <div class="cs-acc-body" id="faq-body-4" hidden>
                    Absolutely. Our REST API lets you trigger document sends, capture signature events via webhooks, and sync with your CRM, ERP, or any custom application.
                </div>
            </div>

            <div class="cs-acc-item" id="faq-5">
                <button class="cs-acc-trigger" aria-expanded="false" aria-controls="faq-body-5">
                    What support options are available?
                    <svg class="cs-acc-chevron" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><polyline points="6 9 12 15 18 9"/></svg>
                </button>
                <div class="cs-acc-body" id="faq-body-5" hidden>
                    Free users get email support. Premium subscribers receive priority live chat with a guaranteed 5-minute response during business hours, plus dedicated phone support.
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════════
     7. TRUST BADGES (above footer on all pages via footer.blade.php)
═══════════════════════════════════════════════════════════ --}}
<section class="cs-trust-badges" aria-label="Security certifications">
    <div class="container">
        <div class="cs-trust-badges__row">
            <div class="cs-trust-chip">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                SSL Secured
            </div>
            <div class="cs-trust-chip">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                GDPR Compliant
            </div>
            <div class="cs-trust-chip">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                eIDAS Ready
            </div>
            <div class="cs-trust-chip">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="2" y="2" width="20" height="20" rx="4"/><path d="M7 12l3 3 7-7"/></svg>
                256-bit Encryption
            </div>
            <div class="cs-trust-chip">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                99.9% Uptime SLA
            </div>
        </div>
    </div>
</section>

@include('frontend.include.footer')
</div>{{-- end cs-page-body --}}
</body>
</html>
