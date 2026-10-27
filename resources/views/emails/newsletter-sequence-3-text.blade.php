Hello,

Planning a trip raises questions. Here are two we hear often:

@foreach ($faq as $item)
{{ $item['q'] }}
{{ $item['a'] }}

@endforeach
Tell us your dates and who is coming, and we will reply within 24 business hours with options and upfront prices.
Request a custom quote: {{ $contactUrl }}
@if ($whatsappNumber)
WhatsApp: https://wa.me/{{ $whatsappNumber }}
@endif

The Vumbi Ventures team
Nakuru, Kenya

Unsubscribe: {{ $unsubscribeUrl }}
Privacy policy: {{ $privacyUrl }}
