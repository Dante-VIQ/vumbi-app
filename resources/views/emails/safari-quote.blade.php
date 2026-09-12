<x-mail::message>
# New Safari Expedition Quote Request

**Destination:** {{ $quote->destination }}  
**Client Name:** {{ $quote->name }}  
**Email:** [{{ $quote->email }}](mailto:{{ $quote->email }})  
**Phone / WhatsApp:** [{{ $quote->phone }}](https://wa.me/{{ preg_replace('/[^0-9]/', '', $quote->phone) }})  
**Travel Month:** {{ \Carbon\Carbon::parse($quote->travel_month)->format('F Y') }}  
**Travelers:** {{ $quote->travelers }} Pax  

@if($quote->notes)
### Special Requests & Preferences:
> {{ $quote->notes }}
@endif

<x-mail::button :url="config('app.url') . '/admin/quotes/' . $quote->id">
View in Admin Panel
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>