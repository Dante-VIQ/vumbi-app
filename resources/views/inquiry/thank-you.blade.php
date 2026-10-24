<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Request Received — Vumbi Ventures</title>
    <meta name="robots" content="noindex, nofollow">
    @vite(['resources/css/app.css'])
    @include('partials.gtag')
</head>
<body class="bg-[#F8F5F2] min-h-screen flex items-center justify-center p-6">

    <div class="max-w-xl w-full bg-white rounded-3xl shadow-xl border border-black/5 p-8 md:p-12 text-center">

        <div class="w-16 h-16 mx-auto rounded-full bg-emerald-100 flex items-center justify-center mb-6">
            <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2.5"
                 viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
        </div>

        <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900">Request received.</h1>
        <p class="text-slate-600 mt-3 leading-relaxed">
            Thanks, {{ $quote->name }}. We've logged your inquiry for
            <strong class="text-[#8B5A2B]">{{ $quote->destination }}</strong>
            and emailed a confirmation to <strong>{{ $quote->email }}</strong>.
        </p>

        <div class="mt-6 text-left bg-slate-50 rounded-2xl p-5 text-sm">
            <div class="flex justify-between py-1">
                <span class="text-slate-500">Reference</span>
                <span class="font-bold text-slate-900">{{ $quote->reference }}</span>
            </div>
            <div class="flex justify-between py-1">
                <span class="text-slate-500">Travel month</span>
                <span class="font-semibold">{{ $quote->travel_month }}</span>
            </div>
            <div class="flex justify-between py-1">
                <span class="text-slate-500">Travelers</span>
                <span class="font-semibold">{{ $quote->travelers }}</span>
            </div>
        </div>

        <p class="text-xs text-slate-500 mt-6">
            Our team responds within 24 hours. Need it faster?
        </p>

        <div class="flex flex-col sm:flex-row gap-3 mt-4">
            <a href="https://wa.me/{{ config('services.whatsapp.number') }}?text=Hi%20Vumbi%20Ventures,%20my%20reference%20is%20{{ $quote->reference }}"
               target="_blank"
               data-gtag-event="whatsapp_click" data-gtag-params='{"placement":"quote_thank_you"}'
               class="flex-1 px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl transition text-center">
                WhatsApp Us
            </a>
            <a href="{{ route('home') }}"
               class="flex-1 px-6 py-3 border border-slate-300 hover:bg-slate-50 text-slate-800 font-bold rounded-xl transition text-center">
                Back to Home
            </a>
        </div>

        <p class="text-sm text-slate-600 mt-6">
            While you wait:
            <a href="{{ route('safari-checklist') }}" class="font-semibold text-[#8B5A2B] underline"
               data-gtag-event="checklist_click" data-gtag-params='{"placement":"quote_thank_you"}'>use our free Kenya first-safari checklist</a>.
        </p>
    </div>

    @include('partials.analytics-events')
    {{-- The quote request is the main conversion. Fire once per request: a refresh must not double count. --}}
    <script>
        (function () {
            var ref = @json($quote->reference);
            var key = 'lead_tracked_' + ref;
            try { if (sessionStorage.getItem(key)) return; } catch (e) {}
            if (typeof gtag === 'function') {
                gtag('event', 'generate_lead', {
                    lead_type: 'safari_quote',
                    destination: @json($quote->destination),
                    travelers: @json((int) $quote->travelers),
                    travel_month: @json($quote->travel_month),
                });
                try { sessionStorage.setItem(key, '1'); } catch (e) {}
            }
        })();
    </script>
</body>
</html>