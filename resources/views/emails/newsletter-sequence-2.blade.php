<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Trips picked for you</title></head>
<body style="margin:0;padding:0;background:#FCFAF7;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif;color:#1A1A1A;">
    <div style="max-width:600px;margin:0 auto;background:#ffffff;">
        <div style="background:#8B5A2B;padding:24px 32px;color:#ffffff;">
            <div style="font-size:13px;letter-spacing:1px;text-transform:uppercase;opacity:.85;">Vumbi Ventures</div>
            <h1 style="margin:6px 0 0;font-size:22px;line-height:1.3;">Trips picked for you</h1>
        </div>
        <div style="padding:28px 32px;font-size:16px;line-height:1.65;">
            <p style="margin:0 0 16px;">Hello,</p>
            @if ($style)
                <p style="margin:0 0 16px;">{{ $style['intro'] }}</p>
            @else
                <p style="margin:0 0 16px;">Here are a few trips from the local partners we work with. Prices are per person, in US dollars.</p>
            @endif

            @if ($tours->isNotEmpty())
                <ul style="margin:0 0 20px;padding-left:20px;">
                    @foreach ($tours as $tour)
                        <li style="margin-bottom:8px;"><a href="{{ route('tours.show', $tour->slug) }}" style="color:#8B5A2B;">{{ $tour->title }}</a>, from {{ \App\Support\Money::usd($tour->price) }}</li>
                    @endforeach
                </ul>
            @endif

            <p style="margin:0 0 24px;">
                <a href="{{ $styleUrl }}" style="display:inline-block;background:#8B5A2B;color:#ffffff;text-decoration:none;padding:12px 20px;border-radius:10px;font-weight:600;">{{ $style ? 'See all ' . strtolower($style['name']) . ' trips' : 'Find your trip' }}</a>
            </p>
            <p style="margin:0 0 16px;font-size:14px;">Not sure what fits? <a href="{{ $quizUrl }}" style="color:#8B5A2B;">Take the 1-minute quiz</a>. Still need the planning basics? <a href="{{ $magnet['url'] }}" style="color:#8B5A2B;">{{ $magnet['title'] }}</a>.</p>

            <p style="margin:0;">The Vumbi Ventures team<br>Nakuru, Kenya</p>
        </div>
        <div style="padding:20px 32px;background:#F5EFE6;font-size:12px;line-height:1.6;color:#5C5C5C;">
            You are receiving this because you signed up for Field Notes at vumbiventures.com.<br>
            <a href="{{ $unsubscribeUrl }}" style="color:#5C5C5C;">Unsubscribe</a> &middot;
            <a href="{{ $privacyUrl }}" style="color:#5C5C5C;">Privacy policy</a>
        </div>
    </div>
</body>
</html>
