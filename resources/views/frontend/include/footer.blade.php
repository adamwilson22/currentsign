<footer class="cs-footer" aria-label="Site footer">
    <div class="container">
        <div class="cs-footer__grid">

            <!-- Col 1: Brand -->
            <div class="cs-footer__brand">
                <a href="{{ url('/') }}" aria-label="Current Sign home">
                    <img
                        src="{{ asset('assets_web/img/CS-2-TBG.png') }}"
                        alt="Current Sign"
                        class="cs-footer__logo-img"
                    >
                </a>
                <p class="cs-footer__tagline">Legally binding e-signatures for everyone. Free to start, no printer needed.</p>

                <!-- Newsletter -->
                <form id="subscribe-form" class="cs-footer__subscribe" method="GET" data-url="{{ url('/subscribe-endpoint') }}">
                    @csrf
                    <input type="email" name="email" id="emaill" placeholder="you@company.com" class="cs-footer__email-input" aria-label="Email for newsletter">
                    <button type="submit" class="cs-btn cs-btn--primary cs-btn--sm">Subscribe</button>
                </form>

                <!-- Social icons -->
                <div class="cs-footer__social">
                    <a href="https://linkedin.com" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn" class="cs-social-link">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"/><rect x="2" y="9" width="4" height="12"/><circle cx="4" cy="4" r="2"/></svg>
                    </a>
                    <a href="https://twitter.com" target="_blank" rel="noopener noreferrer" aria-label="Twitter / X" class="cs-social-link">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"/></svg>
                    </a>
                    <a href="https://github.com" target="_blank" rel="noopener noreferrer" aria-label="GitHub" class="cs-social-link">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M9 19c-5 1.5-5-2.5-7-3m14 6v-3.87a3.37 3.37 0 0 0-.94-2.61c3.14-.35 6.44-1.54 6.44-7A5.44 5.44 0 0 0 20 4.77 5.07 5.07 0 0 0 19.91 1S18.73.65 16 2.48a13.38 13.38 0 0 0-7 0C6.27.65 5.09 1 5.09 1A5.07 5.07 0 0 0 5 4.77a5.44 5.44 0 0 0-1.5 3.78c0 5.42 3.3 6.61 6.44 7A3.37 3.37 0 0 0 9 18.13V22"/></svg>
                    </a>
                </div>
            </div>

            <!-- Col 2: Quick Links -->
            <div class="cs-footer__col">
                <h4 class="cs-footer__col-title">Quick Links</h4>
                <ul class="cs-footer__list">
                    <li><a href="{{ url('/') }}">Home</a></li>
                    <li><a href="{{ url('/#cs-about') }}">About Us</a></li>
                    <li><a href="{{ url('/pricing') }}">Pricing</a></li>
                    <li><a href="{{ url('/contact') }}">Contact Us</a></li>
                </ul>
            </div>

            <!-- Col 3: Important -->
            <div class="cs-footer__col">
                <h4 class="cs-footer__col-title">Important</h4>
                <ul class="cs-footer__list">
                    <li><a href="{{ url('/user/documents') }}">Documents</a></li>
                    <li><a href="{{ url('/user/notes') }}">Notes</a></li>
                    <li><a href="{{ url('/contact') }}">Support</a></li>
                    <li><a href="{{ url('/pricing') }}">Plans</a></li>
                </ul>
            </div>

            <!-- Col 4: Legal -->
            <div class="cs-footer__col">
                <h4 class="cs-footer__col-title">Legal</h4>
                <ul class="cs-footer__list">
                    <li><a href="{{ url('/privacy-policy') }}">Privacy Policy</a></li>
                    <li><a href="{{ url('/termsofuse') }}">Terms of Use</a></li>
                    <li><a href="{{ url('/legal-disclaimer') }}">Legal Disclaimer</a></li>
                    <li><a href="{{ url('/privacy-policy') }}">Cookie Policy</a></li>
                </ul>
            </div>
        </div>

        <div class="cs-footer__bottom">
            <p>© 2026 Current Sign. All rights reserved.</p>
            <p class="cs-footer__bottom-links">
                <a href="{{ url('/privacy-policy') }}">Privacy</a>
                <span>·</span>
                <a href="{{ url('/termsofuse') }}">Terms</a>
                <span>·</span>
                <a href="{{ url('/contact') }}">Contact</a>
            </p>
        </div>
    </div>
</footer>

<!-- ─── SCRIPTS ──────────────────────────────────────────────── -->
<script src="{{ asset('assets_web/js/jquery-3.2.1.min.js') }}"></script>
<script src="{{ asset('assets_web/js/pre-loader.js') }}"></script>
<script src="{{ asset('assets_web/assets/bootstrap/js/popper.min.js') }}"></script>
<script src="{{ asset('assets_web/assets/bootstrap/js/bootstrap.min.js') }}"></script>

<script>
/* ── Navbar scroll behaviour ─────────────────────────────── */
(function () {
    var nav = document.getElementById('cs-nav');
    var lastY = 0;
    window.addEventListener('scroll', function () {
        var y = window.scrollY;
        if (y > 20) {
            nav.classList.add('cs-nav--scrolled');
        } else {
            nav.classList.remove('cs-nav--scrolled');
        }
        lastY = y;
    }, { passive: true });
})();

/* ── Mobile hamburger ────────────────────────────────────── */
(function () {
    var btn     = document.getElementById('cs-hamburger');
    var drawer  = document.getElementById('cs-drawer');
    var overlay = document.getElementById('cs-overlay');

    function open() {
        drawer.classList.add('is-open');
        overlay.classList.add('is-open');
        btn.setAttribute('aria-expanded', 'true');
        drawer.setAttribute('aria-hidden', 'false');
        btn.classList.add('is-open');
    }
    function close() {
        drawer.classList.remove('is-open');
        overlay.classList.remove('is-open');
        btn.setAttribute('aria-expanded', 'false');
        drawer.setAttribute('aria-hidden', 'true');
        btn.classList.remove('is-open');
    }

    btn.addEventListener('click', function () {
        drawer.classList.contains('is-open') ? close() : open();
    });
    overlay.addEventListener('click', close);
    document.querySelectorAll('.cs-drawer__link').forEach(function (a) {
        a.addEventListener('click', close);
    });
})();

/* ── Feature tabs ────────────────────────────────────────── */
(function () {
    var tabs   = document.querySelectorAll('.cs-feat__tab');
    var panels = document.querySelectorAll('.cs-feat__panel');

    tabs.forEach(function (tab) {
        tab.addEventListener('click', function () {
            tabs.forEach(function (t) { t.classList.remove('active'); t.setAttribute('aria-selected', 'false'); });
            panels.forEach(function (p) { p.classList.remove('active'); });
            tab.classList.add('active');
            tab.setAttribute('aria-selected', 'true');
            var target = document.getElementById(tab.getAttribute('aria-controls'));
            if (target) target.classList.add('active');
        });
    });
})();

/* ── FAQ accordion ───────────────────────────────────────── */
(function () {
    document.querySelectorAll('.cs-acc-trigger').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var expanded = btn.getAttribute('aria-expanded') === 'true';
            var body     = document.getElementById(btn.getAttribute('aria-controls'));

            // Close all first
            document.querySelectorAll('.cs-acc-trigger').forEach(function (b) {
                b.setAttribute('aria-expanded', 'false');
                b.closest('.cs-acc-item').classList.remove('is-open');
                var bd = document.getElementById(b.getAttribute('aria-controls'));
                if (bd) bd.hidden = true;
            });

            if (!expanded) {
                btn.setAttribute('aria-expanded', 'true');
                btn.closest('.cs-acc-item').classList.add('is-open');
                if (body) body.hidden = false;
            }
        });
    });
})();

/* ── Count-up animation ──────────────────────────────────── */
(function () {
    var nums = document.querySelectorAll('.cs-stat__num');
    if (!nums.length) return;

    function countUp(el) {
        var target   = parseInt(el.getAttribute('data-target'), 10);
        var duration = 1800;
        var start    = performance.now();
        function step(now) {
            var elapsed  = now - start;
            var progress = Math.min(elapsed / duration, 1);
            var ease     = 1 - Math.pow(1 - progress, 3);
            el.textContent = Math.floor(ease * target).toLocaleString();
            if (progress < 1) requestAnimationFrame(step);
        }
        requestAnimationFrame(step);
    }

    var io = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (entry.isIntersecting) {
                countUp(entry.target);
                io.unobserve(entry.target);
            }
        });
    }, { threshold: 0.5 });

    nums.forEach(function (n) { io.observe(n); });
})();

/* ── Testimonial carousel ────────────────────────────────── */
(function () {
    var cards  = document.querySelectorAll('.cs-testi-card');
    var dots   = document.querySelectorAll('.cs-carousel__dot');
    var idx    = 0;
    var timer;

    function show(n) {
        cards.forEach(function (c, i) { c.classList.toggle('active', i === n); });
        dots.forEach(function (d, i)  { d.classList.toggle('active', i === n); });
        idx = n;
    }

    function next() { show((idx + 1) % cards.length); }

    function start() { timer = setInterval(next, 4000); }
    function stop()  { clearInterval(timer); }

    dots.forEach(function (d) {
        d.addEventListener('click', function () {
            stop(); show(parseInt(d.getAttribute('data-idx'), 10)); start();
        });
    });

    var carousel = document.getElementById('cs-carousel');
    if (carousel) {
        carousel.addEventListener('mouseenter', stop);
        carousel.addEventListener('mouseleave', start);
    }

    show(0);
    start();
})();

/* ── Scroll-in fade animations ───────────────────────────── */
(function () {
    var els = document.querySelectorAll('.cs-fade-in');
    if (!('IntersectionObserver' in window)) {
        els.forEach(function (el) { el.classList.add('is-visible'); });
        return;
    }
    var io = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                io.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });
    els.forEach(function (el) { io.observe(el); });
})();

/* ── Newsletter AJAX ─────────────────────────────────────── */
$(document).ready(function () {
    $('#subscribe-form').on('submit', function (e) {
        e.preventDefault();
        var email = $('#emaill').val();
        var token = $('meta[name="csrf-token"]').attr('content');
        var url   = $('#subscribe-form').data('url');
        if (email) {
            $.ajax({
                url: url, type: 'POST',
                data: { _token: token, email: email },
                success: function (r) { $('input[name="email"]').val(''); alert(r.message || 'Subscribed!'); },
                error:   function ()  { alert('Something went wrong, please try again.'); }
            });
        }
    });
});

/* ── Smooth scroll for anchor links ─────────────────────── */
document.querySelectorAll('a[href^="#"], a[href*="/#"]').forEach(function (a) {
    a.addEventListener('click', function (e) {
        var href = a.getAttribute('href');
        var hash = href.includes('#') ? '#' + href.split('#')[1] : null;
        if (!hash) return;
        var target = document.querySelector(hash);
        if (target) {
            e.preventDefault();
            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    });
});
</script>
