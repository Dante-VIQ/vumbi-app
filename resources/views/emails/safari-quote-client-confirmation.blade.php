<!DOCTYPE html>
<html lang="en" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="x-apple-disable-message-reformatting">
    <meta name="format-detection" content="telephone=no,address=no,email=no,date=no,url=no">
    <meta name="color-scheme" content="light dark">
    <meta name="supported-color-schemes" content="light dark">
    <title>We received your request</title>

    <!--[if mso]>
    <noscript>
        <xml>
            <o:OfficeDocumentSettings>
                <o:PixelsPerInch>96</o:PixelsPerInch>
            </o:OfficeDocumentSettings>
        </xml>
    </noscript>
    <![endif]-->

    <style>
        html, body { margin:0 !important; padding:0 !important; height:100% !important; width:100% !important; }
        * { -ms-text-size-adjust:100%; -webkit-text-size-adjust:100%; }
        table, td { mso-table-lspace:0pt !important; mso-table-rspace:0pt !important; }
        table { border-spacing:0 !important; border-collapse:collapse !important; }
        img { -ms-interpolation-mode:bicubic; border:0; height:auto; line-height:100%; outline:none; text-decoration:none; }
        a { text-decoration:none; }

        @media screen and (max-width: 600px) {
            .container      { width:100% !important; max-width:100% !important; }
            .px             { padding-left:20px !important; padding-right:20px !important; }
            .stack          { display:block !important; width:100% !important; }
            .h1             { font-size:22px !important; line-height:28px !important; }
            .btn a          { display:block !important; width:100% !important; padding:16px 20px !important; }
            .hide-mobile    { display:none !important; }
            .text-center-sm { text-align:center !important; }
        }

        @media (prefers-color-scheme: dark) {
            .dm-bg     { background:#1a1a1a !important; }
            .dm-card   { background:#262626 !important; }
            .dm-text   { color:#e5e5e5 !important; }
            .dm-muted  { color:#a3a3a3 !important; }
            .dm-border { border-color:#404040 !important; }
            .dm-inner  { background:#1f1f1f !important; }
        }
    </style>
</head>
<body class="dm-bg" style="margin:0; padding:0; background:#F8F5F2; font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif;">

    {{-- Preheader --}}
    <div style="display:none; font-size:1px; color:#F8F5F2; line-height:1px; max-height:0; max-width:0; opacity:0; overflow:hidden; mso-hide:all;">
        Thanks {{ $quote->name }} — we've received your {{ $quote->destination }} request and will reply within 24 hours.
    </div>

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" class="dm-bg" style="background:#F8F5F2;">
        <tr>
            <td align="center" style="padding:24px 12px;">

                <!--[if mso]>
                <table role="presentation" width="600" cellpadding="0" cellspacing="0" border="0"><tr><td>
                <![endif]-->

                <table role="presentation" class="container dm-card" width="600" cellpadding="0" cellspacing="0" border="0"
                       style="width:600px; max-width:600px; background:#ffffff; border-radius:16px; overflow:hidden;">

                    {{-- Brand header --}}
                    <tr>
                        <td class="px" style="background:#8B5A2B; padding:28px 32px; text-align:center;">
                            <div style="font-size:16px; font-weight:800; color:#ffffff; letter-spacing:2px; text-transform:uppercase;">
                                Vumbi Ventures
                            </div>
                            <div class="hide-mobile" style="font-size:11px; font-weight:600; color:#F5D7B8; letter-spacing:1.5px; text-transform:uppercase; margin-top:4px;">
                                East Africa Travel & Transport
                            </div>
                        </td>
                    </tr>

                    {{-- Greeting --}}
                    <tr>
                        <td class="px" style="padding:32px 32px 8px; text-align:center;">
                            <div style="font-size:48px; line-height:48px; margin-bottom:12px;">🦁</div>
                            <h1 class="h1 dm-text" style="margin:0; font-size:26px; line-height:34px; font-weight:800; color:#1A1A1A;">
                                Karibu, {{ $quote->name }}!
                            </h1>
                            <p class="dm-text" style="margin:12px 0 0; font-size:15px; line-height:24px; color:#404040;">
                                We've received your inquiry for
                                <strong style="color:#8B5A2B;">{{ $quote->destination }}</strong>
                                and our team is matching you with vetted local partners.
                            </p>
                        </td>
                    </tr>

                    {{-- Reference card --}}
                    <tr>
                        <td class="px" style="padding:24px 32px;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0"
                                   class="dm-inner" style="background:#FCFAF7; border-radius:12px; border:1px solid #F0E6D8;">
                                <tr>
                                    <td style="padding:20px 24px;">

                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                            <tr>
                                                <td class="dm-muted" style="font-size:11px; font-weight:700; letter-spacing:1.5px; text-transform:uppercase; color:#8B5A2B;">
                                                    Your Reference
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="dm-text" style="padding-top:6px; font-size:20px; font-weight:800; color:#1A1A1A; font-family:'SF Mono',Consolas,monospace; letter-spacing:1px;">
                                                    {{ $quote->reference }}
                                                </td>
                                            </tr>
                                        </table>

                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0"
                                               style="margin-top:20px; border-top:1px solid #F0E6D8;" class="dm-border">
                                            <tr>
                                                <td class="stack" style="padding:16px 0 0; width:50%; vertical-align:top;">
                                                    <div class="dm-muted" style="font-size:11px; font-weight:600; letter-spacing:1px; text-transform:uppercase; color:#737373;">Travel Month</div>
                                                    <div class="dm-text" style="padding-top:4px; font-size:15px; font-weight:700; color:#1A1A1A;">{{ $quote->travel_month }}</div>
                                                </td>
                                                <td class="stack" style="padding:16px 0 0; width:50%; vertical-align:top;">
                                                    <div class="dm-muted" style="font-size:11px; font-weight:600; letter-spacing:1px; text-transform:uppercase; color:#737373;">Travelers</div>
                                                    <div class="dm-text" style="padding-top:4px; font-size:15px; font-weight:700; color:#1A1A1A;">{{ $quote->travelers }} {{ Str::plural('person', $quote->travelers) }}</div>
                                                </td>
                                            </tr>
                                        </table>

                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    {{-- Timeline / what happens next --}}
                    <tr>
                        <td class="px" style="padding:0 32px 24px;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td class="dm-text" style="padding-bottom:16px; font-size:15px; font-weight:700; color:#1A1A1A;">
                                        What happens next
                                    </td>
                                </tr>

                                @php
                                    $steps = [
                                        ['1', 'Within 24 hours', 'A curated itinerary and partner pricing lands in your inbox.'],
                                        ['2', 'You review', 'Tweak dates, add activities, or request a different vehicle class.'],
                                        ['3', 'We lock it in', 'Deposit secures your preferred 4x4 and lodge slots.'],
                                    ];
                                @endphp

                                @foreach($steps as [$n, $title, $body])
                                    <tr>
                                        <td style="padding:8px 0;">
                                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                                <tr>
                                                    <td width="36" style="width:36px; vertical-align:top;">
                                                        <div style="width:28px; height:28px; border-radius:50%; background:#8B5A2B; color:#ffffff; font-size:13px; font-weight:800; text-align:center; line-height:28px;">
                                                            {{ $n }}
                                                        </div>
                                                    </td>
                                                    <td style="vertical-align:top; padding-left:12px;">
                                                        <div class="dm-text" style="font-size:14px; font-weight:700; color:#1A1A1A;">{{ $title }}</div>
                                                        <div class="dm-muted" style="padding-top:2px; font-size:13px; line-height:20px; color:#525252;">{{ $body }}</div>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </td>
                    </tr>

                    {{-- CTA --}}
                    <tr>
                        <td class="px" style="padding:8px 32px 32px;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" class="btn">
                                <tr>
                                    <td align="center">
                                        <a href="https://wa.me/254734591543?text=Hi%20Vumbi%20Ventures,%20my%20reference%20is%20{{ urlencode($quote->reference) }}"
                                           style="display:inline-block; padding:16px 28px; background:#059669; color:#ffffff; text-decoration:none; border-radius:10px; font-weight:700; font-size:15px; min-height:44px; line-height:20px;">
                                            💬&nbsp; Chat on WhatsApp
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center" style="padding-top:10px;">
                                        <a href="{{ route('home') }}"
                                           style="display:inline-block; padding:14px 24px; background:#ffffff; color:#8B5A2B; text-decoration:none; border-radius:10px; font-weight:700; font-size:14px; border:2px solid #E8D5BE; line-height:20px;">
                                            Browse More Safaris
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td class="px" style="background:#FAFAF9; padding:24px 32px; border-top:1px solid #F0F0F0;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td align="center" class="dm-muted" style="font-size:12px; line-height:20px; color:#737373;">
                                        <strong class="dm-text" style="color:#1A1A1A;">Vumbi Ventures</strong><br>
                                        Nakuru, Kenya · +254 734 591 543<br>
                                        <a href="https://vumbiventures.com" style="color:#8B5A2B; text-decoration:underline;">vumbiventures.com</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center" class="dm-muted" style="padding-top:12px; font-size:11px; line-height:16px; color:#a3a3a3;">
                                        This is an automated confirmation. Reply directly to this email to reach a real human.
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>

                <!--[if mso]>
                </td></tr></table>
                <![endif]-->

                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr><td height="16" style="font-size:0; line-height:0;">&nbsp;</td></tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>