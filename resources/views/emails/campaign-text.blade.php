{{ $campaign->subject }}

{!! trim(strip_tags($bodyHtml)) !!}
@if ($ctaUrl && $campaign->cta_label)

{{ $campaign->cta_label }}: {{ $ctaUrl }}
@endif

--
You are receiving this because you signed up for Field Notes at vumbiventures.com.
Unsubscribe: {{ $unsubscribeUrl }}
Privacy policy: {{ $privacyUrl }}
Vumbi Ventures, Nakuru, Kenya
