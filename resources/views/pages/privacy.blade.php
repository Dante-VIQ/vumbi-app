@extends('layouts.app')

@section('title', 'Privacy Policy')
@section('description', 'How Vumbi Ventures collects, uses and protects your personal information when you use vumbiventures.com, request a safari quote or join our newsletter.')

@section('content')
    <div class="bg-[#FCFAF7] text-[#1A1A1A]">
        <div class="container mx-auto px-6 max-w-3xl pt-28 pb-20 md:pt-36 prose prose-lg max-w-none prose-headings:font-semibold prose-headings:tracking-tight prose-a:text-[#8B5A2B]">
            <h1>Privacy Policy</h1>
            <p><em>Last updated: 11 October 2026</em></p>

            <p>This policy explains what personal information Vumbi Ventures collects on vumbiventures.com, why we collect it, who we share it with and what choices you have. We are based in Nakuru, Kenya, and we aim to handle your information in line with Kenya's Data Protection Act, 2019.</p>

            <h2>Who we are</h2>
            <p>Vumbi Ventures is a Kenya-based travel and storytelling company. If you have a question about this policy or your information, email <a href="mailto:africa@vumbiventures.com">africa@vumbiventures.com</a> or use our <a href="{{ route('contact') }}">contact page</a>.</p>

            <h2>What we collect</h2>
            <ul>
                <li><strong>Newsletter:</strong> your email address and the time you signed up.</li>
                <li><strong>Safari quote and booking requests:</strong> your name, email address, phone or WhatsApp number, travel month or start date, number of travellers and any notes you add.</li>
                <li><strong>Contact form:</strong> your name, email address, optional phone number, the type of enquiry, subject and message.</li>
                <li><strong>Accounts:</strong> if you create an account, your name, email address and a password, which we store in scrambled (hashed) form.</li>
                <li><strong>Link clicks:</strong> when you click a booking or partner link, we record which link, the page you came from and your IP address, in some cases stored only in a hashed form. We use this to see which content helps readers plan trips and to credit our partners correctly.</li>
                <li><strong>Analytics and cookies:</strong> we use Google Analytics and Ahrefs Web Analytics to see how the site is used (pages visited, device type, approximate location). These services may set cookies or similar identifiers.</li>
                <li><strong>Partner widgets and scripts:</strong> some pages load tools from travel partners, such as GetYourGuide and Travelpayouts, which may set their own cookies. Their own policies apply to what they collect.</li>
            </ul>

            <h2>How we use it</h2>
            <ul>
                <li>To reply to your enquiries and prepare itineraries and quotes.</li>
                <li>To send you Field Notes, our newsletter, if you subscribed.</li>
                <li>To keep the site secure and stop spam and abuse.</li>
                <li>To understand which pages and stories are useful, and to improve them.</li>
                <li>To earn affiliate commission when you book through a partner link.</li>
            </ul>

            <h2>Affiliate links</h2>
            <p>Some links on this site go to travel partners. If you book through them we may earn a commission at no extra cost to you. This does not change the price you pay.</p>

            <h2>Who we share it with</h2>
            <ul>
                <li><strong>Travel partners and local operators</strong> who fulfil a quote or booking you asked for. We share only what they need to respond to you.</li>
                <li><strong>Service providers</strong> who help us run the site and send email, such as our hosting provider and our email service provider. They may only use your information to provide their service to us.</li>
                <li><strong>Analytics providers</strong> described above.</li>
                <li><strong>Authorities</strong>, where the law requires it.</li>
            </ul>
            <p>We do not sell your personal information. Some providers are based outside Kenya, so your information may be processed in other countries.</p>

            <h2>How long we keep it</h2>
            <p>We keep newsletter addresses until you unsubscribe. We keep enquiry and booking details for as long as we need them to deal with your request, and for a reasonable period afterwards for our records. You can ask us to delete your information sooner.</p>

            <h2>Your choices and rights</h2>
            <ul>
                <li><strong>Unsubscribe</strong> from the newsletter at any time using the link at the bottom of every email, or by writing to us.</li>
                <li>You can ask to see, correct or delete the personal information we hold about you, and to object to or restrict how we use it.</li>
                <li>You can withdraw consent you gave us at any time.</li>
                <li>You can block or delete cookies in your browser settings.</li>
            </ul>
            <p>To use any of these rights, email <a href="mailto:africa@vumbiventures.com">africa@vumbiventures.com</a>. If you are unhappy with how we handle your information, you can also complain to Kenya's Office of the Data Protection Commissioner.</p>

            <h2>Children</h2>
            <p>This site is not aimed at children, and we do not knowingly collect their personal information.</p>

            <h2>Changes to this policy</h2>
            <p>We may update this policy as the site changes. The date at the top shows when it was last revised.</p>
        </div>
    </div>
@endsection
