@extends('layouts.app')

@section('title', 'Terms of Service')
@section('description', 'The terms that apply when you use vumbiventures.com, request a safari quote or book a trip through Vumbi Ventures and its travel partners.')

@section('content')
    {{-- DRAFT: written from how the site works today. Have it reviewed before relying on it legally. --}}
    <div class="bg-[#FCFAF7] text-[#1A1A1A]">
        <div class="container mx-auto px-6 max-w-3xl pt-28 pb-20 md:pt-36 prose prose-lg max-w-none prose-headings:font-semibold prose-headings:tracking-tight prose-a:text-[#8B5A2B]">
            <h1>Terms of Service</h1>
            <p><em>Last updated: 24 October 2026</em></p>

            <p>These terms apply when you use vumbiventures.com, request a quote or send us an enquiry. By using the site you agree to them. If you do not agree, please do not use the site.</p>

            <h2>What Vumbi Ventures does</h2>
            <p>Vumbi Ventures is a Kenya-based travel and storytelling company. We publish travel guides and stories, and we connect travellers with licensed local operators, vehicle partners and established travel platforms. In most cases we introduce you to a partner who then provides the trip. We are not the operator of those trips unless we tell you so in writing.</p>

            <h2>Quotes, bookings and partners</h2>
            <ul>
                <li>Prices shown on the site are starting prices per person and are a guide only. The price that applies is the one confirmed in your quote or on the partner's booking page.</li>
                <li>When you book through a partner's site, such as GetYourGuide, that partner's own terms, payment rules and cancellation policy apply to your booking.</li>
                <li>A quote request is not a booking. A booking exists only once it is confirmed in writing by us or by the partner.</li>
                <li>Availability, vehicles, guides and itineraries can change because of weather, park rules, safety or other things outside our control.</li>
            </ul>

            <h2>Affiliate links</h2>
            <p>Some links on this site go to travel partners. If you book through them we may earn a commission at no extra cost to you. See our <a href="{{ route('privacy') }}">Privacy Policy</a> for how we record link clicks.</p>

            <h2>Travel information</h2>
            <p>Our guides, including information on visas, entry rules, health, safety, weather and the best time to visit, are general guidance written in good faith. They can go out of date. Please confirm entry requirements, vaccinations and travel advisories with official sources before you travel, and arrange suitable travel insurance.</p>

            <h2>Your responsibilities</h2>
            <ul>
                <li>Give us accurate information when you send a quote request or enquiry.</li>
                <li>Follow the laws, park rules and safety instructions of the places you visit.</li>
                <li>Do not misuse the site, attempt to disrupt it, or copy content in bulk.</li>
            </ul>

            <h2>Our content</h2>
            <p>The articles, text, images and design on this site belong to Vumbi Ventures or its contributors and are protected by copyright. You may share links and short quotations with credit. Please ask us before republishing anything else.</p>

            <h2>Limits on our responsibility</h2>
            <p>We do our best to keep the site accurate and available, but we provide it as is. To the extent the law allows, we are not responsible for losses that come from trips provided by partners, from changes or cancellations by partners, or from relying on information on the site. Nothing in these terms limits any right you have under Kenyan consumer law that cannot be limited.</p>

            <h2>Changes to these terms</h2>
            <p>We may update these terms from time to time. The date at the top shows when they last changed. Continuing to use the site after a change means you accept the updated terms.</p>

            <h2>Governing law and contact</h2>
            <p>These terms are governed by the laws of Kenya. Questions about them can be sent to <a href="mailto:africa@vumbiventures.com">africa@vumbiventures.com</a> or through our <a href="{{ route('contact') }}">contact page</a>.</p>
        </div>
    </div>
@endsection
