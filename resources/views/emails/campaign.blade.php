<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>{{ $campaign->subject }}</title></head>
<body style="margin:0;padding:0;background:#FCFAF7;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Arial,sans-serif;color:#1A1A1A;">
    @if ($campaign->preheader)
        <div style="display:none;max-height:0;overflow:hidden;opacity:0;">{{ $campaign->preheader }}</div>
    @endif
    <div style="max-width:600px;margin:0 auto;background:#ffffff;">
        <div style="background:#8B5A2B;padding:24px 32px;color:#ffffff;">
            <div style="font-size:13px;letter-spacing:1px;text-transform:uppercase;opacity:.85;">Vumbi Ventures</div>
            <h1 style="margin:6px 0 0;font-size:22px;line-height:1.3;">{{ $campaign->subject }}</h1>
        </div>
        <div style="padding:28px 32px;font-size:16px;line-height:1.65;">
            <style>.vc a{color:#8B5A2B}.vc p{margin:0 0 16px}.vc ul,.vc ol{margin:0 0 16px;padding-left:22px}.vc h2,.vc h3{margin:20px 0 8px}</style>
            <div class="vc">{!! $bodyHtml !!}</div>
            @if ($ctaUrl && $campaign->cta_label)
                <p style="margin:24px 0 0;">
                    <a href="{{ $ctaUrl }}" style="display:inline-block;background:#8B5A2B;color:#ffffff;text-decoration:none;padding:12px 20px;border-radius:10px;font-weight:600;">{{ $campaign->cta_label }}</a>
                </p>
            @endif
        </div>
        <div style="padding:20px 32px;background:#F5EFE6;font-size:12px;line-height:1.6;color:#5C5C5C;">
            @if ($isTest)
                This is a test send. In the real email, this line is replaced by each subscriber's own unsubscribe link.<br>
            @endif
            You are receiving this because you signed up for Field Notes at vumbiventures.com.<br>
            <a href="{{ $unsubscribeUrl }}" style="color:#5C5C5C;">Unsubscribe</a> &middot;
            <a href="{{ $privacyUrl }}" style="color:#5C5C5C;">Privacy policy</a><br>
            Vumbi Ventures, Nakuru, Kenya
        </div>
    </div>
</body>
</html>
