@extends('layouts.studio')

@section('content')
<article class="max-w-4xl mx-auto px-4 pt-14 pb-20">

    <a href="{{ url('/studio') }}#client-work"
        class="inline-flex items-center gap-2 text-xs font-semibold text-slate-400 hover:text-amber-300 transition-colors mb-8">
        &larr; All client work
    </a>

    <header class="mb-12">
        <p class="text-xs font-bold text-indigo-400 tracking-widest uppercase mb-3">Case study</p>
        <h1 class="text-4xl sm:text-5xl font-black tracking-tight text-white leading-[1.1] mb-4">{{ $study['name'] }}</h1>
        <p class="text-lg text-slate-300 max-w-2xl">{{ $study['summary'] }}</p>

        <dl class="mt-8 grid grid-cols-2 sm:grid-cols-4 gap-4 text-sm">
            <div class="rounded-2xl bg-white/5 border border-white/10 p-4">
                <dt class="text-[11px] uppercase tracking-wider text-slate-400">Industry</dt>
                <dd class="text-white font-semibold mt-1">{{ $study['industry'] }}</dd>
            </div>
            <div class="rounded-2xl bg-white/5 border border-white/10 p-4">
                <dt class="text-[11px] uppercase tracking-wider text-slate-400">Market</dt>
                <dd class="text-white font-semibold mt-1">{{ $study['market'] }}</dd>
            </div>
            @if (! empty($study['stack']))
                <div class="rounded-2xl bg-white/5 border border-white/10 p-4">
                    <dt class="text-[11px] uppercase tracking-wider text-slate-400">Built with</dt>
                    <dd class="text-white font-semibold mt-1">{{ implode(', ', $study['stack']) }}</dd>
                </div>
            @endif
            @if (! empty($study['timeline']))
                <div class="rounded-2xl bg-white/5 border border-white/10 p-4">
                    <dt class="text-[11px] uppercase tracking-wider text-slate-400">Timeline</dt>
                    <dd class="text-white font-semibold mt-1">{{ $study['timeline'] }}</dd>
                </div>
            @endif
            <div class="rounded-2xl bg-white/5 border border-white/10 p-4">
                <dt class="text-[11px] uppercase tracking-wider text-slate-400">Live site</dt>
                <dd class="mt-1">
                    <a href="{{ $study['url'] }}" target="_blank" rel="noopener noreferrer"
                        class="text-amber-300 hover:text-amber-200 font-semibold break-all">{{ $study['url_label'] }} &#8599;</a>
                </dd>
            </div>
        </dl>
    </header>

    <section class="mb-12">
        <h2 class="text-2xl font-extrabold text-white mb-3">The brief</h2>
        <p class="text-slate-300 leading-relaxed">{{ $study['brief'] }}</p>
    </section>

    <section class="mb-12">
        <h2 class="text-2xl font-extrabold text-white mb-4">What we built</h2>
        <ul class="space-y-3">
            @foreach ($study['built'] as $item)
                <li class="flex gap-3 text-slate-300">
                    <span class="text-emerald-400 mt-0.5">&#10003;</span>
                    <span>{{ $item }}</span>
                </li>
            @endforeach
        </ul>
    </section>

    @if (! empty($study['engineering']))
        <section class="mb-12">
            <h2 class="text-2xl font-extrabold text-white mb-4">Engineering notes</h2>
            <ul class="space-y-3">
                @foreach ($study['engineering'] as $item)
                    <li class="flex gap-3 text-slate-300">
                        <span class="text-indigo-400 mt-0.5">&#9656;</span>
                        <span>{{ $item }}</span>
                    </li>
                @endforeach
            </ul>
        </section>
    @endif

    @if (! empty($study['results']))
        <section class="mb-12">
            <h2 class="text-2xl font-extrabold text-white mb-4">Results</h2>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                @foreach ($study['results'] as $result)
                    <div class="rounded-2xl bg-white/5 border border-white/10 p-5">
                        <p class="text-3xl font-black text-white">{{ $result['value'] }}</p>
                        <p class="text-sm text-slate-400 mt-1">{{ $result['label'] }}</p>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    @if (! empty($study['quote']['text']))
        <figure class="mb-12 rounded-3xl border border-white/10 bg-white/5 p-8">
            <blockquote class="text-lg text-slate-200 leading-relaxed">&ldquo;{{ $study['quote']['text'] }}&rdquo;</blockquote>
            <figcaption class="mt-4 text-sm text-slate-400">
                {{ $study['quote']['name'] }}@if (! empty($study['quote']['role'])), {{ $study['quote']['role'] }}@endif
            </figcaption>
        </figure>
    @endif

    <section class="rounded-3xl border border-white/10 bg-gradient-to-b from-indigo-950/30 to-slate-900/40 p-8 text-center">
        <h2 class="text-2xl font-extrabold text-white mb-3">Need something like this?</h2>
        <p class="text-slate-300 max-w-xl mx-auto mb-6">
            Tell us how your operation runs today and we will reply with an approach, a fixed scope and a timeline.
        </p>
        <div class="flex flex-wrap justify-center gap-3">
            <a href="mailto:studio@vumbiventures.com?subject={{ rawurlencode('Project enquiry: similar to '.$study['name']) }}"
                class="px-6 py-3 rounded-2xl bg-white text-slate-950 font-black text-sm hover:bg-slate-200 transition-all">
                Email the studio
            </a>
            <a href="https://wa.me/254734591543?text={{ rawurlencode('Hello, I read the '.$study['name'].' case study and would like to discuss a project.') }}"
                target="_blank" rel="noopener noreferrer"
                class="px-6 py-3 rounded-2xl bg-emerald-500 text-slate-950 font-black text-sm hover:bg-emerald-400 transition-all">
                Chat on WhatsApp
            </a>
        </div>
    </section>
</article>

<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'CreativeWork',
    'name' => $study['name'].' case study',
    'description' => $study['summary'],
    'url' => route('studio.case-study', $slug),
    'publisher' => ['@type' => 'Organization', 'name' => 'Vumbi Ventures Tech Studio'],
], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
@endsection
