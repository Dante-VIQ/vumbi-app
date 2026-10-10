@extends('layouts.app')

@section('title', 'Kenya First-Safari Planning Checklist')
@section('description', 'A free one-page checklist for planning your first safari in Kenya: when to go, where to go, entry and health basics, booking questions and what to pack.')
@section('keywords', 'Kenya safari checklist, first safari Kenya, safari planning, Kenya travel checklist, what to pack for safari')

@php
    $sections = [
        [
            'title' => '1. Choose when to go',
            'items' => [
                'Decide what matters most: the Great Migration, fewer crowds, lower prices or green scenery.',
                'For migration river crossings, plan for July to October, with August and September the usual peak. Herds follow the rains, so no operator can promise a crossing on a given date.',
                'Expect the main rainy seasons to bring quieter parks and lower rates. Our <a href="' . url('/blog/31') . '">best-time guide</a> compares each month.',
                'Keep two or three travel dates open if you can; wildlife timing moves from year to year.',
            ],
        ],
        [
            'title' => '2. Choose where to go',
            'items' => [
                'Shortlist two or three parks, not six. A first safari works best with 3 to 5 nights in one or two areas.',
                'Match parks to your wishes: Maasai Mara for big cats and migration, Amboseli for elephants with Kilimanjaro behind them, Lake Nakuru for rhinos and birdlife.',
                'Add a coast or beach leg only if you have time; road and flight times between regions are longer than maps suggest.',
                'Read <a href="' . url('/blog/32') . '">how to plan your first safari in Kenya</a> before you pick.',
            ],
        ],
        [
            'title' => '3. Sort entry and health',
            'items' => [
                'Check whether you need a Kenya eTA (electronic travel authorisation) and apply only on the <a href="https://www.etakenya.go.ke" target="_blank" rel="noopener">official government portal</a>; unofficial look-alike sites exist. Apply well before you fly, since processing time is not guaranteed.',
                'Make sure your passport is valid for at least six months after you arrive, and carry at least two blank pages.',
                'Check whether a yellow fever certificate applies. It depends on the countries you travel from or through, not only your nationality.',
                'Ask a travel clinic or doctor about malaria prevention and routine vaccines, ideally 6 to 8 weeks before travel.',
                'Buy travel insurance that covers medical care and evacuation.',
            ],
        ],
        [
            'title' => '4. Book with confidence',
            'items' => [
                'Ask each operator what is included: park fees, transport, meals, guide, drinks and airport transfers.',
                'Confirm the vehicle (a 4x4 with a pop-up roof), the number of travellers per vehicle and a window seat for everyone.',
                'Ask who the local operator is, and whether the guide is certified.',
                'Get the cancellation and payment terms in writing.',
            ],
        ],
        [
            'title' => '5. Pack smart',
            'items' => [
                'Neutral, layered clothing (khaki, olive, brown); early game drives are cold, midday is hot.',
                'A warm jacket, hat, sunscreen and sunglasses.',
                'Closed, comfortable shoes and a light rain layer.',
                'Binoculars and a camera with spare batteries and memory cards.',
                'Insect repellent, any prescription medicines in original packaging, and a small first-aid kit.',
                'Cash in small notes for tips and local purchases, and a card for larger payments.',
                'A soft-sided bag; small safari planes often limit hard cases.',
            ],
        ],
        [
            'title' => '6. Before you leave home',
            'items' => [
                'Download offline maps and save your operator\'s WhatsApp number.',
                'Share your itinerary and insurance details with someone at home.',
                'Re-check your eTA approval, passport and flight times a week before departure.',
            ],
        ],
    ];
@endphp

@section('content')
    <div class="bg-[#FCFAF7] text-[#1A1A1A]">
        <section class="pt-28 pb-8 md:pt-36">
            <div class="container mx-auto px-6 max-w-3xl">
                <p class="text-sm font-semibold uppercase tracking-wider text-[#8B5A2B] mb-3">Field Notes · Free checklist</p>
                <h1 class="text-3xl md:text-5xl font-semibold leading-tight tracking-tight mb-5">Kenya first-safari planning checklist</h1>
                <p class="text-lg text-[#3A3A3A] leading-relaxed mb-6">
                    Six short steps that take you from "when should we go?" to a confirmed booking. Tick items as you go.
                    We left out prices and fee amounts on purpose, since both change; each item says where to check.
                </p>
                <button type="button" onclick="window.print()"
                    class="print:hidden inline-flex items-center gap-2 border border-[#8B5A2B]/30 bg-white hover:bg-[#F5EFE6] text-[#8B5A2B] px-4 py-2 rounded-xl text-sm font-medium transition">
                    Print this checklist
                </button>
            </div>
        </section>

        <section class="pb-12">
            <div class="container mx-auto px-6 max-w-3xl space-y-10">
                @foreach ($sections as $section)
                    <div>
                        <h2 class="text-2xl font-semibold mb-4">{{ $section['title'] }}</h2>
                        <ul class="space-y-3">
                            @foreach ($section['items'] as $item)
                                <li>
                                    <label class="flex items-start gap-3 cursor-pointer">
                                        <input type="checkbox" class="mt-1.5 h-5 w-5 shrink-0 rounded border-[#8B5A2B]/40 text-[#8B5A2B] focus:ring-[#8B5A2B]">
                                        <span class="text-[#3A3A3A] leading-relaxed [&_a]:text-[#8B5A2B] [&_a]:underline">{!! $item !!}</span>
                                    </label>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach

                <p class="text-sm italic text-[#5C5C5C]">
                    Checked 11 October 2026. Entry rules and fees change: confirm on the
                    <a href="https://www.etakenya.go.ke" target="_blank" rel="noopener" class="text-[#8B5A2B] underline">official eTA portal</a>
                    and with your airline before you travel.
                </p>
            </div>
        </section>

        <section class="print:hidden pb-20">
            <div class="container mx-auto px-6 max-w-3xl grid gap-6 md:grid-cols-2">
                <x-whatsapp-cta title="Kenya first-safari planning checklist" placement="checklist_page" />

                <div class="bg-[#1A1A1A] p-6 rounded-2xl">
                    <h3 class="font-semibold text-lg text-white mb-1">Get Field Notes by email</h3>
                    <p class="text-sm text-[#B0B0B0] mb-4 leading-relaxed">Monthly stories and safari planning tips from across Africa.</p>
                    <livewire:newsletter-subscribe variant="dark" placement="checklist" />
                </div>
            </div>
        </section>
    </div>
@endsection
