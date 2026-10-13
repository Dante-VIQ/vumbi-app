<x-mail::message>
# Thank you, {{ $quote->name }}!

We've received your safari quote request and our team is already on it. Expect to hear from us within **24 hours**.

Here's a summary of what you sent us:

**Destination:** {{ $quote->destination }}  
**Travel Month:** {{ \Carbon\Carbon::parse($quote->travel_month)->format('F Y') }}  
**Travelers:** {{ $quote->travelers }} {{ \Illuminate\Support\Str::plural('traveler', $quote->travelers) }}  

@if($quote->notes)
**Your notes:**
> {{ $quote->notes }}
@endif

<x-mail::button :url="'https://wa.me/' . preg_replace('/[^0-9]/', '', config('mail.admin_whatsapp', ''))">
Chat with us on WhatsApp
</x-mail::button>

Safe travels,<br>
{{ config('app.name') }}
</x-mail::message>