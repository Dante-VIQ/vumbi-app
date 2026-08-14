<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Booking Lead</title>
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
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #F0E9E0;
        }
        .detail-label {
            color: #6B6B6B;
            font-size: 14px;
            font-weight: 500;
        }
        .detail-value {
            font-weight: 600;
            color: #1A1A1A;
        }
        .badge {
            display: inline-block;
            background: #F5EFE6;
            color: #8B5A2B;
            padding: 4px 12px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
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
            <h1>🔔 New Booking Lead</h1>
            <p>A customer has requested a booking</p>
        </div>
        <div class="content">
            <div style="margin-bottom: 24px;">
                <span class="badge">Status: {{ ucfirst($lead->status) }}</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Customer Name</span>
                <span class="detail-value">{{ $lead->first_name }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Phone (WhatsApp)</span>
                <span class="detail-value">
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $lead->phone) }}" target="_blank">
                        {{ $lead->phone }}
                    </a>
                </span>
            </div>
            @if($lead->email)
            <div class="detail-row">
                <span class="detail-label">Email</span>
                <span class="detail-value">{{ $lead->email }}</span>
            </div>
            @endif
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
            <div class="detail-row">
                <span class="detail-label">Commission ({{ $lead->commission_percent }}%)</span>
                <span class="detail-value">KSh {{ number_format($lead->estimated_price * $lead->commission_percent / 100) }}</span>
            </div>
            @endif

            <div style="margin-top: 32px; text-align: center;">
                <a href="{{ url('/admin/leads/' . $lead->id) }}"
                   style="display: inline-block; background: #8B5A2B; color: white; padding: 12px 24px; border-radius: 999px; text-decoration: none; font-weight: 600;">
                    View Lead in Admin
                </a>
            </div>
        </div>
        <div class="footer">
            Vumbi Ventures — Booking System Notification
        </div>
    </div>
</body>
</html>