<?php

namespace App\Models;

use App\Support\Persona;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class EmailCampaign extends Model
{
    public const DRAFT = 'draft';
    public const SENDING = 'sending';
    public const SENT = 'sent';

    protected $fillable = [
        'name', 'subject', 'preheader', 'body', 'cta_label', 'cta_url',
        'audience', 'status', 'recipients_count', 'started_at', 'completed_at', 'created_by',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function sends(): HasMany
    {
        return $this->hasMany(EmailCampaignSend::class);
    }

    public function isDraft(): bool
    {
        return $this->status === self::DRAFT;
    }

    /** Audience options for the form: key => label. */
    public static function audiences(): array
    {
        $out = ['all' => 'Everyone on the list'];
        foreach (Persona::keys() as $key) {
            $out[$key] = 'Only ' . (Persona::label($key) ?? $key) . 's';
        }

        return $out;
    }

    public function audienceQuery(): Builder
    {
        return NewsletterSubscriber::query()
            ->when($this->audience !== 'all', fn ($q) => $q->where('persona', $this->audience));
    }

    /** Markdown to safe HTML: raw HTML in the body is stripped, unsafe links are dropped. */
    public function bodyHtml(): string
    {
        $html = Str::markdown((string) $this->body, ['html_input' => 'strip', 'allow_unsafe_links' => false]);
        $host = parse_url(url('/'), PHP_URL_HOST);

        // Tag links back to our own site so GA attributes the visit to this campaign.
        return preg_replace_callback('/href="([^"]+)"/', function ($m) use ($host) {
            $href = html_entity_decode($m[1]);
            // Relative links ("/blog") would be broken inside an email, so make them absolute.
            if (str_starts_with($href, '/') && ! str_starts_with($href, '//')) {
                $href = url($href);
            }

            return 'href="' . $this->withUtm($href, $host) . '"';
        }, $html);
    }

    public function ctaUrl(): ?string
    {
        if (! $this->cta_url) {
            return null;
        }

        $url = str_starts_with($this->cta_url, '/') ? url($this->cta_url) : $this->cta_url;

        return $this->withUtm($url, parse_url(url('/'), PHP_URL_HOST));
    }

    private function withUtm(string $url, ?string $host): string
    {
        if (parse_url($url, PHP_URL_HOST) !== $host) {
            return e($url);
        }

        $params = http_build_query([
            'utm_source' => 'email',
            'utm_medium' => 'newsletter',
            'utm_campaign' => Str::slug($this->name) ?: 'campaign-' . $this->id,
        ]);

        $sep = str_contains($url, '?') ? '&' : '?';
        $fragment = '';
        if (str_contains($url, '#')) {
            [$url, $frag] = explode('#', $url, 2);
            $fragment = '#' . $frag;
        }

        return e($url . $sep . $params . $fragment);
    }
}
