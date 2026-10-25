@extends('layouts.studio')

@php
    $packageNames = ['starter' => 'Starter ($700)', 'growth' => 'Growth ($1,250)', 'scale' => 'Scale (custom)'];
    $selectedPackage = $packageNames[request('package')] ?? null;
    $defaultSubject = $selectedPackage ? "Enquiry about the {$selectedPackage} package" : '';
@endphp

@section('content')
    <section class="max-w-3xl mx-auto px-4 pt-16 pb-24">
        <div class="text-center mb-10">
            <h1 class="text-3xl md:text-4xl font-extrabold text-white">Contact Vumbi Tech Studio</h1>
            <p class="text-slate-400 mt-3 max-w-xl mx-auto">
                Tell us about the website, web app or growth project you have in mind. We reply within 24 business hours.
            </p>
            <p class="text-xs text-slate-500 mt-3">
                Planning a trip instead? <a href="{{ route('contact') }}" class="text-amber-400 hover:text-amber-300">Contact our travel team</a>.
            </p>
        </div>

        @if (session('success'))
            <div class="mb-6 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-300 text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 p-4 rounded-xl bg-red-500/10 border border-red-500/30 text-red-300 text-sm">
                Please check the highlighted details and try again.
            </div>
        @endif

        <form action="{{ route('contact.submit') }}" method="POST"
            class="space-y-5 bg-slate-900/60 border border-white/10 rounded-3xl p-6 md:p-10 backdrop-blur-xl">
            @csrf

            <div class="grid md:grid-cols-2 gap-5">
                <div>
                    <label for="name" class="block text-sm font-medium text-slate-300 mb-1">Full name *</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required autocomplete="name"
                        class="w-full rounded-xl bg-slate-950/60 border border-white/10 px-4 py-3 text-white placeholder-slate-500 focus:border-amber-400 focus:outline-none">
                    @error('name') <span class="text-xs text-red-400">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-slate-300 mb-1">Email *</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required autocomplete="email"
                        class="w-full rounded-xl bg-slate-950/60 border border-white/10 px-4 py-3 text-white placeholder-slate-500 focus:border-amber-400 focus:outline-none">
                    @error('email') <span class="text-xs text-red-400">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-5">
                <div>
                    <label for="phone" class="block text-sm font-medium text-slate-300 mb-1">Phone (optional)</label>
                    <input type="tel" name="phone" id="phone" value="{{ old('phone') }}" autocomplete="tel"
                        class="w-full rounded-xl bg-slate-950/60 border border-white/10 px-4 py-3 text-white placeholder-slate-500 focus:border-amber-400 focus:outline-none">
                </div>
                <div>
                    <label for="inquiry_type" class="block text-sm font-medium text-slate-300 mb-1">I'm interested in *</label>
                    <select name="inquiry_type" id="inquiry_type" required
                        class="w-full rounded-xl bg-slate-950/60 border border-white/10 px-4 py-3 text-white focus:border-amber-400 focus:outline-none">
                        <option value="web_dev" @selected(old('inquiry_type', 'web_dev') === 'web_dev')>Website or web application</option>
                        <option value="seo" @selected(old('inquiry_type') === 'seo')>SEO &amp; digital growth</option>
                        <option value="other" @selected(old('inquiry_type') === 'other')>Platform licensing or something else</option>
                    </select>
                    @error('inquiry_type') <span class="text-xs text-red-400">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-5">
                <div>
                    <label for="subject" class="block text-sm font-medium text-slate-300 mb-1">Subject</label>
                    <input type="text" name="subject" id="subject" value="{{ old('subject', $defaultSubject) }}"
                        class="w-full rounded-xl bg-slate-950/60 border border-white/10 px-4 py-3 text-white placeholder-slate-500 focus:border-amber-400 focus:outline-none">
                </div>
                <div>
                    <label for="website" class="block text-sm font-medium text-slate-300 mb-1">Current website (optional)</label>
                    <input type="url" name="website" id="website" value="{{ old('website') }}" placeholder="https://yourbrand.com"
                        class="w-full rounded-xl bg-slate-950/60 border border-white/10 px-4 py-3 text-white placeholder-slate-500 focus:border-amber-400 focus:outline-none">
                    @error('website') <span class="text-xs text-red-400">{{ $message }}</span> @enderror
                </div>
            </div>

            <div>
                <label for="message" class="block text-sm font-medium text-slate-300 mb-1">Project details *</label>
                <textarea name="message" id="message" rows="5" required
                    placeholder="What do you want to build, who is it for, and roughly when do you need it?"
                    class="w-full rounded-xl bg-slate-950/60 border border-white/10 px-4 py-3 text-white placeholder-slate-500 focus:border-amber-400 focus:outline-none">{{ old('message') }}</textarea>
                @error('message') <span class="text-xs text-red-400">{{ $message }}</span> @enderror
            </div>

            <div class="flex items-start gap-3">
                <input type="checkbox" name="consent" id="consent" required class="mt-1 w-4 h-4 rounded border-slate-600">
                <label for="consent" class="text-xs text-slate-400">
                    I agree to be contacted about this enquiry. We protect your data and never spam.
                    See our <a href="{{ route('privacy') }}" class="underline hover:text-white">privacy policy</a>.
                </label>
            </div>

            <button type="submit"
                class="w-full py-3.5 rounded-xl bg-amber-400 hover:bg-amber-300 text-slate-950 font-bold transition">
                Send enquiry
            </button>
        </form>
    </section>
@endsection
