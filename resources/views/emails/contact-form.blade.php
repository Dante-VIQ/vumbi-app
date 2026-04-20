<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>New Contact Form Submission</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; line-height: 1.6; color: #1A1A1A; max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #8B5A2B; color: white; padding: 20px; border-radius: 8px 8px 0 0; }
        .content { background: #FCFAF7; padding: 20px; border: 1px solid #E5E5E5; border-top: none; border-radius: 0 0 8px 8px; }
        .field { margin-bottom: 15px; }
        .label { font-weight: 600; color: #5C5C5C; font-size: 14px; }
        .value { color: #1A1A1A; }
        .inquiry-badge { display: inline-block; background: #F5EFE6; padding: 4px 12px; border-radius: 20px; font-size: 14px; color: #8B5A2B; }
        .footer { margin-top: 20px; font-size: 12px; color: #5C5C5C; text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <h2 style="margin: 0;">New Contact Form Submission</h2>
    </div>
    
    <div class="content">
        <div class="field">
            <div class="label">Name</div>
            <div class="value">{{ $data['name'] }}</div>
        </div>
        
        <div class="field">
            <div class="label">Email</div>
            <div class="value">{{ $data['email'] }}</div>
        </div>
        
        @if(!empty($data['phone']))
        <div class="field">
            <div class="label">Phone</div>
            <div class="value">{{ $data['phone'] }}</div>
        </div>
        @endif
        
        <div class="field">
            <div class="label">Inquiry Type</div>
            <div class="value">
                <span class="inquiry-badge">
                    @switch($data['inquiry_type'])
                        @case('travel') Travel Booking / Discovery @break
                        @case('web_dev') Web Development Services @break
                        @case('partnership') Partnership Opportunity @break
                        @case('other') Other @break
                        @default {{ $data['inquiry_type'] }}
                    @endswitch
                </span>
            </div>
        </div>
        
        @if(!empty($data['subject']))
        <div class="field">
            <div class="label">Subject</div>
            <div class="value">{{ $data['subject'] }}</div>
        </div>
        @endif
        
        <div class="field">
            <div class="label">Message</div>
            <div class="value">{{ $data['message'] }}</div>
        </div>
    </div>
    
    <div class="footer">
        <p>This email was sent from the contact form on vumbiventures.com</p>
    </div>
</body>
</html>