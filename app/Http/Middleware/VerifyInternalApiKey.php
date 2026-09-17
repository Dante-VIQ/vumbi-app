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

        // ✅ Read from config, not env()
        $valid = config('internal.api_key');

        if (!$key || !$valid || !hash_equals($valid, $key)) {
            Log::warning('Internal API unauthorized', [
                'ip'    => $request->ip(),
                'path'  => $request->path(),
                'has_key' => !empty($key),
                'config_loaded' => !empty($valid),
            ]);
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Optional IP allowlist
        $allowed = config('internal.allowed_ips', []);
        if (!empty($allowed) && !in_array($request->ip(), $allowed, true)) {
            Log::warning('Internal API IP not allowed', [
                'ip'   => $request->ip(),
                'path' => $request->path(),
            ]);
            return response()->json(['error' => 'Forbidden'], 403);
        }

        return $next($request);
    }
}