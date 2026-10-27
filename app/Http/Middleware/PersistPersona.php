<?php

namespace App\Http\Middleware;

use App\Support\Persona;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Response;

/**
 * Remembers the persona a visitor picks (?persona=adventure|youth|group) for a year.
 * ?persona=all clears it. Nothing personal is stored, only the persona key.
 */
class PersistPersona
{
    public function handle(Request $request, Closure $next): Response
    {
        $value = $request->query('persona');

        // Ignore anything that is not a plain string (e.g. ?persona[]=x).
        if ($request->isMethod('GET') && is_string($value)) {
            $raw = strtolower(trim($value));

            if (in_array($raw, ['all', 'any', 'clear', ''], true)) {
                Cookie::queue(Cookie::forget(Persona::COOKIE));
            } elseif ($persona = Persona::normalize($raw)) {
                Cookie::queue(Persona::COOKIE, $persona, 60 * 24 * 365);
            }
        }

        $response = $next($request);

        $this->inferFromBrowsing($request);

        return $response;
    }

    /**
     * With no explicit choice, remember which persona the viewed trips are tagged for.
     * After two views pointing at the same persona we start using it as a soft guess
     * (a separate cookie, so it never overrides what a visitor picks).
     */
    private function inferFromBrowsing(Request $request): void
    {
        if (! $request->isMethod('GET') || $request->route()?->getName() !== 'tours.show') {
            return;
        }
        if ($request->cookie(Persona::COOKIE)) {
            return; // they already told us
        }

        $package = $request->route('package');
        $tags = is_object($package) && is_array($package->audiences ?? null) ? $package->audiences : [];
        $tags = array_values(array_intersect($tags, Persona::keys()));
        if ($tags === []) {
            return;
        }

        $seen = json_decode((string) $request->cookie('vumbi_seen'), true);
        $seen = is_array($seen) ? array_intersect_key(array_map('intval', $seen), array_flip(Persona::keys())) : [];
        foreach ($tags as $tag) {
            $seen[$tag] = min(50, ($seen[$tag] ?? 0) + 1);
        }
        Cookie::queue('vumbi_seen', json_encode($seen), 60 * 24 * 30);

        arsort($seen);
        $top = array_key_first($seen);
        $second = array_values($seen)[1] ?? 0;
        if ($seen[$top] >= 2 && $seen[$top] > $second) {
            Cookie::queue(Persona::GUESS_COOKIE, $top, 60 * 24 * 30);
        }
    }
}
