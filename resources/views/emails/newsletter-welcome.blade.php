<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Field Notes</title>
</head>
<body style="margin:0;padding:0;background:#FCFAF7;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif;color:#1A1A1A;">
    <div style="max-width:600px;margin:0 auto;background:#ffffff;">
        <div style="background:#8B5A2B;padding:28px 32px;color:#ffffff;">
            <div style="font-size:13px;letter-spacing:1px;text-transform:uppercase;opacity:.85;">Vumbi Ventures</div>
            <h1 style="margin:6px 0 0;font-size:24px;line-height:1.3;">Welcome to Field Notes</h1>
        </div>

        <div style="padding:28px 32px;font-size:16px;line-height:1.65;">
            <p style="margin:0 0 16px;">Hello,</p>
            <p style="margin:0 0 16px;">Thank you for signing up. As promised, here is the planning checklist we wish every first-time safari traveller had:</p>

            <p style="margin:0 0 24px;">
                <a href="{{ $checklistUrl }}" style="display:inline-block;background:#8B5A2B;color:#ffffff;text-decoration:none;padding:12px 20px;border-radius:10px;font-weight:600;">Open the Kenya first-safari checklist</a>
            </p>

            <p style="margin:0 0 8px;">If you only read three things first:</p>
            <ol style="margin:0 0 20px;padding-left:22px;">
                <li style="margin-bottom:6px;"><a href="{{ url('/blog/32') }}" style="color:#8B5A2B;">How to plan your first safari in Kenya</a></li>
                <li style="margin-bottom:6px;"><a href="{{ url('/blog/31') }}" style="color:#8B5A2B;">The best time to visit Kenya for a safari</a></li>
                <li><a href="{{ url('/blog/33') }}" style="color:#8B5A2B;">The unwritten rules of attending a Kenyan wedding</a>, a taste of our storytelling</li>
            </ol>

            <p style="margin:0 0 16px;">
                Already thinking about a trip? Reply to this email
                @if ($whatsappNumber)
                    or message us on <a href="https://wa.me/{{ $whatsappNumber }}" style="color:#8B5A2B;">WhatsApp</a>
                @endif
                and we will match you with vetted local partners and send a custom itinerary or quote within 24 hours.
            </p>

            <p style="margin:0 0 16px;">We send Field Notes about once a month.</p>
            <p style="margin:0;">The Vumbi Ventures team<br>Nakuru, Kenya</p>
        </div>

        <div style="padding:20px 32px;background:#F5EFE6;font-size:12px;line-height:1.6;color:#5C5C5C;">
            You are receiving this because you signed up for Field Notes at vumbiventures.com.<br>
            <a href="{{ $unsubscribeUrl }}" style="color:#5C5C5C;">Unsubscribe</a> ·
            <a href="{{ $privacyUrl }}" style="color:#5C5C5C;">Privacy policy</a><br>
            Vumbi Ventures, Nakuru, Kenya
        </div>
    </div>
</body>
</html>
