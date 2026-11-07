Hello,

@if ($style){{ $style['intro'] }}
@else
Here are a few trips from the local partners we work with. Prices are per person, in US dollars.
@endif

@foreach ($tours as $tour)
- {{ $tour->title }}, from {{ \App\Support\Money::usd($tour->price) }}: {{ route('tours.show', $tour->slug) }}
@endforeach

{{ $style ? 'See all ' . strtolower($style['name']) . ' trips' : 'Find your trip' }}: {{ $styleUrl }}
Not sure what fits? Take the 1-minute quiz: {{ $quizUrl }}
{{ $magnet['title'] }}: {{ $magnet['url'] }}

The Vumbi Ventures team
Nakuru, Kenya

Unsubscribe: {{ $unsubscribeUrl }}
Privacy policy: {{ $privacyUrl }}
