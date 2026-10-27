{{-- Shared conversion-event tracking. Included from layouts/app.blade.php and
     layouts/booking.blade.php, after the gtag() setup above. Safe to include
     even where gtag isn't defined (non-production) — every call is guarded. --}}
<script>
    (function () {
        // Persona (adventure | youth | group) chosen on /discover. The server keeps it in a
        // cookie; mirror it to localStorage and attach it to GA so conversions can be split by it.
        var persona = '';
        try {
            var meta = document.querySelector('meta[name="vumbi-persona"]');
            persona = meta ? (meta.getAttribute('content') || '') : '';
            if (persona) {
                localStorage.setItem('vumbi_persona', persona);
            } else if (window.location.search.indexOf('persona=') === -1) {
                persona = localStorage.getItem('vumbi_persona') || '';
            }
        } catch (e) {}

        function track(eventName, params) {
            if (typeof gtag === 'function') {
                if (persona) {
                    gtag('set', 'user_properties', { persona: persona });
                }
                gtag('event', eventName, params || {});
            }
        }

        // Visits that arrive from an AI assistant or answer engine. GA4 also reports these as referrals;
        // this event makes them easy to chart on their own and tie to the persona.
        try {
            var ref = document.referrer || '';
            var engines = {
                'chatgpt.com': 'chatgpt', 'chat.openai.com': 'chatgpt', 'perplexity.ai': 'perplexity',
                'claude.ai': 'claude', 'gemini.google.com': 'gemini', 'copilot.microsoft.com': 'copilot',
                'you.com': 'you', 'phind.com': 'phind', 'kagi.com': 'kagi', 'duckduckgo.com/?q=': null
            };
            var utm = (window.location.search.match(/utm_source=([^&]+)/) || [])[1] || '';
            Object.keys(engines).forEach(function (host) {
                if (engines[host] && (ref.indexOf(host) !== -1 || utm.indexOf(engines[host]) === 0) && !window.__aiRefSent) {
                    window.__aiRefSent = true;
                    track('ai_referral', { engine: engines[host], landing: window.location.pathname });
                }
            });
        } catch (e) {}

        // Newsletter signups — every <livewire:newsletter-subscribe> instance
        // dispatches this with its placement (sidebar, inline, endpost, footer).
        document.addEventListener('livewire:initialized', function () {
            Livewire.on('newsletter-subscribed', function (event) {
                var data = Array.isArray(event) ? event[0] : event;
                track('newsletter_signup', { placement: data && data.placement ? data.placement : 'unknown' });
            });

            // Booking modal lead capture.
            Livewire.on('lead-captured', function (event) {
                var data = Array.isArray(event) ? event[0] : event;
                track('generate_lead', {
                    package_id: data && data.packageId ? data.packageId : null,
                    package_title: data && data.packageTitle ? data.packageTitle : null,
                });
            });
        });

        // Generic delegated click tracking: add data-gtag-event="event_name" and
        // (optionally) data-gtag-params='{"key":"value"}' to any element — affiliate
        // links, CTA buttons, etc. — and it's tracked with no extra JS per page.
        document.addEventListener('click', function (e) {
            var el = e.target.closest('[data-gtag-event]');
            if (!el) return;

            var params = {};
            if (el.dataset.gtagParams) {
                try {
                    params = JSON.parse(el.dataset.gtagParams);
                } catch (err) {
                    // Malformed params shouldn't break the click — just track without them.
                }
            }
            track(el.dataset.gtagEvent, params);
        });
    })();
</script>