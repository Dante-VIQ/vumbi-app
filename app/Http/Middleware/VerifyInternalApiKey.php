<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VerifyInternalApiKey
{
    public function handle(Request $request, Closure $next)
    {
        $key = $request->header('X-Internal-Key');
        $valid = env('INTERNAL_API_KEY');

        if (!$key || !$valid || !hash_equals($valid, $key)) {
            Log::warning('Internal API unauthorized', [
                'ip'    => $request->ip(),
                'path'  => $request->path(),
                'has_key' => !empty($key),
            ]);
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Optional IP allowlist
        $allowed = trim((string) env('INTERNAL_ALLOWED_IPS', ''));
        if ($allowed !== '') {
            $ips = array_map('trim', explode(',', $allowed));
            if (!in_array($request->ip(), $ips, true)) {
                Log::warning('Internal API IP not allowed', [
                    'ip'   => $request->ip(),
                    'path' => $request->path(),
                ]);
                return response()->json(['error' => 'Forbidden'], 403);
            }
        }

        return $next($request);
    }
}