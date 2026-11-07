<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

/**
 * Counts visits from known AI crawlers (per bot, path and day) so you can see
 * whether answer engines are actually fetching the site. Never blocks or slows a request on failure.
 */
class LogAiCrawlers
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        try {
            $ua = (string) $request->userAgent();
            if ($ua !== '' && $request->isMethod('GET')) {
                foreach (array_keys((array) config('seo.ai_crawlers')) as $bot) {
                    if (stripos($ua, $bot) !== false) {
                        $path = Str::limit('/' . ltrim($request->path(), '/'), 250, '');
                        $day = now()->toDateString();
                        $updated = DB::table('ai_crawler_hits')->where(compact('bot', 'path', 'day'))
                            ->update(['hits' => DB::raw('hits + 1'), 'updated_at' => now()]);
                        if (! $updated) {
                            DB::table('ai_crawler_hits')->insert(compact('bot', 'path', 'day') + ['hits' => 1, 'created_at' => now(), 'updated_at' => now()]);
                        }
                        break;
                    }
                }
            }
        } catch (\Throwable $e) {
            // Logging must never affect the response.
        }

        return $response;
    }
}
