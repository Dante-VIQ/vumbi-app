<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmation</title>
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: #FCFAF7;
            margin: 0;
            padding: 0;
            color: #1A1A1A;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }
        .header {
            background: linear-gradient(135deg, #8B5A2B, #5C3A1E);
            padding: 32px;
            text-align: center;
            color: white;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
        }
        .header p {
            margin: 8px 0 0;
            opacity: 0.9;
            font-size: 14px;
        }
        .content {
            padding: 32px;
        }
        .greeting {
            font-size: 18px;
            margin-bottom: 16px;
        }
        .message {
            color: #5C5C5C;
            line-height: 1.6;
            margin-bottom: 24px;
        }
        .booking-details {
            background: #FCFAF7;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 24px;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #F0E9E0;
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .detail-label {
            color: #6B6B6B;
            font-size: 14px;
        }
        .detail-value {
            font-weight: 600;
        }
        .note {
            background: #F5EFE6;
            border-left: 4px solid #8B5A2B;
            padding: 16px;
            border-radius: 0 8px 8px 0;
            font-size: 14px;
            color: #5C5C5C;
            margin-bottom: 24px;
        }
        .whatsapp-btn {
            display: inline-block;
            background: #25D366;
            color: white;
            padding: 12px 24px;
            border-radius: 999px;
            text-decoration: none;
            font-weight: 600;
            text-align: center;
        }
        .footer {
            padding: 24px;
            text-align: center;
            background: #FCFAF7;
            color: #6B6B6B;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎉 Booking Request Received</h1>
            <p>Vumbi Ventures — Travel Partner Network</p>
        </div>
        <div class="content">
            <div class="greeting">
                Hello {{ $lead->first_name }},
            </div>
            <div class="message">
                Thank you for your interest in <strong>{{ $lead->package_title }}</strong>.
                We've received your booking request and our team is now confirming availability
                with our local partner.
            </div>

            <div class="booking-details">
                <h3 style="margin-top: 0; margin-bottom: 16px;">Booking Summary</h3>
                <div class="detail-row">
                    <span class="detail-label">Package</span>
                    <span class="detail-value">{{ $lead->package_title }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Location</span>
                    <span class="detail-value">{{ $lead->location }}</span>
                </div>
                @if($lead->start_date)
                <div class="detail-row">
                    <span class="detail-label">Preferred Date</span>
                    <span class="detail-value">{{ $lead->start_date->format('F d, Y') }}</span>
                </div>
                @endif
                @if($lead->estimated_price)
                <div class="detail-row">
                    <span class="detail-label">Estimated Price</span>
                    <span class="detail-value">KSh {{ number_format($lead->estimated_price) }}</span>
                </div>
                @endif
            </div>

            <div class="note">
                <strong>💡 What happens next?</strong><br>
                A member of our team will contact you via WhatsApp or email within 24 hours
                to confirm availability and finalize your booking. No payment is required at this stage.
            </div>

            <div style="text-align: center; margin-bottom: 16px;">
                <a href="https://wa.me/254734591543" class="whatsapp-btn">
                    💬 Chat With Us on WhatsApp
                </a>
            </div>
        </div>
        <div class="footer">
            Vumbi Ventures — Discover Africa. Book Extraordinary.<br>
            <a href="{{ url('/discover') }}" style="color: #8B5A2B;">Explore More Destinations</a>
        </div>
    </div>
</body>
</html>