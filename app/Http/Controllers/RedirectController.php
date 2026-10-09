<?php

// app/Http/Controllers/RedirectController.php
namespace App\Http\Controllers;

use App\Models\PartnerPackage;
use App\Models\TourClick;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class RedirectController extends Controller
{
    /**
     * Log an outbound affiliate click, then send the visitor to the partner.
     *
     * The visitor always gets through: a failure while logging the click must
     * never cost a booking, so tracking errors are logged and swallowed.
     */
    public function affiliate(Request $request, string $source, int $tourId): RedirectResponse
    {
        $package = PartnerPackage::active()->find($tourId);

        if (! $package) {
            return redirect()->route('tours.index');
        }

        if (! $package->isAffiliate() || ! $this->isSafeUrl($package->affiliate_url)) {
            // Nothing to hand off to: keep the visitor on the tour page.
            return redirect()->route('tours.show', $package->slug);
        }

        $campaign = Str::limit(
            Str::slug($package->affiliate_source ?: $source, '_'),
            80,
            ''
        );

        $destination = $this->withUtm($package->affiliate_url, $campaign);

        try {
            TourClick::create([
                'partner_package_id' => $package->id,
                'source' => $campaign,
                'destination_url' => $destination,
                'referring_url' => $request->headers->get('referer'),
                'ip_hash' => hash('sha256', (string) $request->ip()),
                'clicked_at' => now(),
            ]);
        } catch (Throwable $e) {
            Log::warning('Affiliate click could not be logged', [
                'package_id' => $package->id,
                'error' => $e->getMessage(),
            ]);
        }

        return redirect()
            ->away($destination)
            ->header('X-Robots-Tag', 'noindex, nofollow');
    }

    private function isSafeUrl(?string $url): bool
    {
        if (! $url) {
            return false;
        }

        $parts = parse_url($url);

        return $parts !== false
            && in_array(strtolower($parts['scheme'] ?? ''), ['http', 'https'], true)
            && ! empty($parts['host']);
    }

    /**
     * Append our UTM tags without overriding any the partner link already has.
     */
    private function withUtm(string $url, string $campaign): string
    {
        $parts = parse_url($url);
        parse_str($parts['query'] ?? '', $existing);

        $utm = array_diff_key([
            'utm_source' => 'vumbiventures',
            'utm_medium' => 'referral',
            'utm_campaign' => $campaign,
        ], $existing);

        if ($utm === []) {
            return $url;
        }

        // Insert before any #fragment so the tags stay in the query string.
        $fragment = '';
        if (($pos = strpos($url, '#')) !== false) {
            $fragment = substr($url, $pos);
            $url = substr($url, 0, $pos);
        }

        $separator = str_contains($url, '?') ? '&' : '?';

        return $url . $separator . http_build_query($utm) . $fragment;
    }
}
