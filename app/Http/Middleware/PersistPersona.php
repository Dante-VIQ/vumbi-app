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

        return $next($request);
    }
}
