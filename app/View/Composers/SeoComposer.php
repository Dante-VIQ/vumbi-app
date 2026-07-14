<?php

namespace App\View\Composers;

use Illuminate\View\View;

class SeoComposer
{
    /**
     * Site-wide fallbacks — used only if a page sets nothing at all.
     * Keep these in sync with the homepage's actual title/description.
     */
    protected string $siteName = 'Vumbi Ventures';
    protected string $defaultTitle = 'Vumbi Ventures – Africa Travel Guides, Safaris & Untold Stories';
    protected string $defaultDescription = 'Explore Africa through curated destination guides, stories and personalized travel planning.';
    protected string $defaultOgImage = 'images/og-default.jpg';

    public function compose(View $view): void
    {
        $data = $view->getData();

        // Page sets these two — everything else derives from them
        // unless explicitly overridden.
        $title       = $data['seo_title'] ?? $this->defaultTitle;
        $description = $data['seo_description'] ?? $this->defaultDescription;

        // Canonical: strip query string by default so filtered/search
        // URLs (e.g. /discover?search=...) don't self-canonicalize into
        // separate indexed pages. Pages can override with $seo_canonical.
        $canonical = $data['seo_canonical'] ?? url(request()->path());

        $view->with('seo', [
            'title'           => $title,
            'description'     => $description,
            'keywords'        => $data['seo_keywords'] ?? null,
            'canonical'       => $canonical,
            'robots'          => $data['seo_robots'] ?? 'index, follow',
            // OG/Twitter derive from title/description automatically.
            // A page only needs $seo_og_title / $seo_og_description if
            // it wants social previews to read differently than the
            // search-result title/description (e.g. shorter, punchier).
            'og_title'        => $data['seo_og_title'] ?? $title,
            'og_description'  => $data['seo_og_description'] ?? $description,
            'og_image'        => $data['seo_og_image'] ?? asset($this->defaultOgImage),
        ]);
    }
}