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