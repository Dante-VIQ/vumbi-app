<!DOCTYPE html>
<html lang="en" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="x-apple-disable-message-reformatting">
    <meta name="format-detection" content="telephone=no,address=no,email=no,date=no,url=no">
    <meta name="color-scheme" content="light dark">
    <meta name="supported-color-schemes" content="light dark">
    <title>New Safari Quote</title>

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
        /* Client resets */
        html, body { margin:0 !important; padding:0 !important; height:100% !important; width:100% !important; }
        * { -ms-text-size-adjust:100%; -webkit-text-size-adjust:100%; }
        table, td { mso-table-lspace:0pt !important; mso-table-rspace:0pt !important; }
        table { border-spacing:0 !important; border-collapse:collapse !important; }
        img { -ms-interpolation-mode:bicubic; border:0; height:auto; line-height:100%; outline:none; text-decoration:none; }
        a { text-decoration:none; }

        /* Mobile */
        @media screen and (max-width: 600px) {
            .container       { width:100% !important; max-width:100% !important; }
            .px              { padding-left:20px !important; padding-right:20px !important; }
            .stack           { display:block !important; width:100% !important; }
            .stack-pad       { padding:0 0 12px 0 !important; }
            .h1              { font-size:22px !important; line-height:28px !important; }
            .label-col       { width:110px !important; }
            .btn a           { display:block !important; width:100% !important; padding:16px 20px !important; }
            .hide-mobile     { display:none !important; }
            .text-center-sm  { text-align:center !important; }
        }

        /* Dark mode */
        @media (prefers-color-scheme: dark) {
            .dm-bg     { background:#1a1a1a !important; }
            .dm-card   { background:#262626 !important; }
            .dm-text   { color:#e5e5e5 !important; }
            .dm-muted  { color:#a3a3a3 !important; }
            .dm-border { border-color:#404040 !important; }
        }
    </style>
</head>
<body class="dm-bg" style="margin:0; padding:0; background:#F8F5F2; font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif;">

    {{-- Preheader (hidden preview text) --}}
    <div style="display:none; font-size:1px; color:#F8F5F2; line-height:1px; max-height:0; max-width:0; opacity:0; overflow:hidden; mso-hide:all;">
        {{ $quote->name }} requested a quote for {{ $quote->destination }} — {{ $quote->travelers }} traveler(s), {{ $quote->travel_month }}. Ref {{ $quote->reference }}.
    </div>

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" class="dm-bg" style="background:#F8F5F2;">
        <tr>
            <td align="center" style="padding:24px 12px;">

                <!--[if mso]>
                <table role="presentation" width="600" cellpadding="0" cellspacing="0" border="0"><tr><td>
                <![endif]-->

                <table role="presentation" class="container dm-card" width="600" cellpadding="0" cellspacing="0" border="0"
                       style="width:600px; max-width:600px; background:#ffffff; border-radius:16px; overflow:hidden;">

                    {{-- Header bar --}}
                    <tr>
                        <td class="px" style="background:#8B5A2B; padding:20px 32px;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td style="font-size:15px; font-weight:700; color:#ffffff; letter-spacing:0.5px;">
                                        VUMBI VENTURES
                                    </td>
                                    <td align="right" class="hide-mobile" style="font-size:11px; font-weight:600; color:#F5D7B8; letter-spacing:1.5px; text-transform:uppercase;">
                                        New Inquiry
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    {{-- Title + reference --}}
                    <tr>
                        <td class="px" style="padding:32px 32px 8px;">
                            <h1 class="h1 dm-text" style="margin:0; font-size:24px; line-height:32px; font-weight:800; color:#1A1A1A;">
                                New Safari Quote Request
                            </h1>
                            <p class="dm-muted" style="margin:8px 0 0; font-size:13px; color:#737373;">
                                Reference:
                                <strong class="dm-text" style="color:#1A1A1A; font-family:'SF Mono',Consolas,monospace;">{{ $quote->reference }}</strong>
                            </p>
                        </td>
                    </tr>

                    {{-- Details --}}
                    <tr>
                        <td class="px" style="padding:24px 32px 8px;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0"
                                   style="font-size:14px; color:#1A1A1A;">

                                @php
                                    $rows = [
                                        'Destination'  => $quote->destination,
                                        'Name'         => $quote->name,
                                        'Email'        => $quote->email,
                                        'Phone'        => $quote->phone,
                                        'Travel month' => $quote->travel_month,
                                        'Travelers'    => $quote->travelers,
                                    ];
                                @endphp

                                @foreach($rows as $label => $value)
                                    <tr>
                                        <td class="label-col dm-muted"
                                            style="width:140px; padding:10px 0; color:#737373; font-size:13px; vertical-align:top; border-bottom:1px solid #F0F0F0;"
                                            class="dm-border">
                                            {{ $label }}
                                        </td>
                                        <td class="dm-text"
                                            style="padding:10px 0; font-size:14px; font-weight:600; color:#1A1A1A; vertical-align:top; border-bottom:1px solid #F0F0F0;"
                                            class="dm-border">
                                            @if($label === 'Email')
                                                <a href="mailto:{{ $value }}" style="color:#8B5A2B; text-decoration:none;">{{ $value }}</a>
                                            @elseif($label === 'Phone')
                                                <a href="tel:{{ $value }}" style="color:#8B5A2B; text-decoration:none;">{{ $value }}</a>
                                            @else
                                                {{ $value }}
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach

                                <tr>
                                    <td class="label-col dm-muted"
                                        style="width:140px; padding:10px 0 0; color:#737373; font-size:13px; vertical-align:top;">
                                        Notes
                                    </td>
                                    <td class="dm-text"
                                        style="padding:10px 0 0; font-size:14px; color:#1A1A1A; vertical-align:top; line-height:22px;">
                                        {{ $quote->notes ?: '—' }}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    {{-- CTA --}}
                    <tr>
                        <td class="px" style="padding:8px 32px 32px;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" class="btn">
                                <tr>
                                    <td align="center" style="padding-top:16px;">
                                        <a href="{{ url('/admin/safari-quotes/' . $quote->id) }}"
                                           style="display:inline-block; padding:14px 28px; background:#8B5A2B; color:#ffffff; text-decoration:none; border-radius:10px; font-weight:700; font-size:15px; min-height:44px; line-height:20px;">
                                            View in Admin →
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center" style="padding-top:10px;">
                                        <a href="mailto:{{ $quote->email }}?subject=Re: Safari Quote {{ $quote->reference }}"
                                           style="display:inline-block; padding:12px 24px; background:#ffffff; color:#8B5A2B; text-decoration:none; border-radius:10px; font-weight:700; font-size:14px; border:2px solid #8B5A2B; line-height:20px;">
                                            Reply to {{ $quote->name }}
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td class="px" style="background:#FAFAF9; padding:20px 32px; border-top:1px solid #F0F0F0;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td class="text-center-sm dm-muted" style="font-size:12px; color:#737373; line-height:18px;">
                                        Submitted from {{ $quote->ip_address }}<br>
                                        {{ $quote->created_at->format('D, d M Y · H:i') }}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>

                <!--[if mso]>
                </td></tr></table>
                <![endif]-->

                {{-- Spacer for mobile --}}
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr><td height="16" style="font-size:0; line-height:0;">&nbsp;</td></tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>