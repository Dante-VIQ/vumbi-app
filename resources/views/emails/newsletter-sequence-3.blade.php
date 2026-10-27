<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Questions before you book?</title></head>
<body style="margin:0;padding:0;background:#FCFAF7;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif;color:#1A1A1A;">
    <div style="max-width:600px;margin:0 auto;background:#ffffff;">
        <div style="background:#8B5A2B;padding:24px 32px;color:#ffffff;">
            <div style="font-size:13px;letter-spacing:1px;text-transform:uppercase;opacity:.85;">Vumbi Ventures</div>
            <h1 style="margin:6px 0 0;font-size:22px;line-height:1.3;">Questions before you book?</h1>
        </div>
        <div style="padding:28px 32px;font-size:16px;line-height:1.65;">
            <p style="margin:0 0 16px;">Hello,</p>
            <p style="margin:0 0 16px;">Planning a trip raises questions. Here are two we hear often{{ $style ? ' from ' . strtolower($style['name']) : '' }}:</p>
            @foreach ($faq as $item)
                <p style="margin:0 0 6px;font-weight:600;">{{ $item['q'] }}</p>
                <p style="margin:0 0 16px;">{{ $item['a'] }}</p>
            @endforeach

            <p style="margin:0 0 16px;">
                Tell us your dates and who is coming, and we will reply within 24 business hours with options and upfront prices from vetted local partners.
            </p>
            <p style="margin:0 0 24px;">
                <a href="{{ $contactUrl }}" style="display:inline-block;background:#8B5A2B;color:#ffffff;text-decoration:none;padding:12px 20px;border-radius:10px;font-weight:600;">Request a custom quote</a>
                @if ($whatsappNumber)
                    &nbsp;<a href="https://wa.me/{{ $whatsappNumber }}" style="color:#8B5A2B;">or chat on WhatsApp</a>
                @endif
            </p>
            <p style="margin:0 0 16px;font-size:14px;">You can also reply to this email.</p>

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
