<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class ForceHttpsAndWww
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $host = $request->getHost();
        $preferredHost = 'www.vumbiventures.com';

        // 1. Force HTTPS
        if (!$request->secure()) {
            return Redirect::secure($request->getRequestUri());
        }

        // 2. Force www
        if ($host !== $preferredHost) {
            $redirectUrl = $request->getScheme() . '://' . $preferredHost . $request->getRequestUri();
            return redirect()->away($redirectUrl, 301);
        }

        return $next($request);
    }
}