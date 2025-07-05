<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

class ApiOauthValidation
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();
        
        if (!$token) {
            return response()->json(['error' => 'Token missing'], 401);
        }

        $cacheKey = 'oauth_token_'.md5($token);
        
        if (Cache::has($cacheKey)) {
            return $next($request);
        }

        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Authorization' => "Bearer $token"
        ])->timeout(3)->get(config('services.api_oauth.validate_url'));

        if ($response->status() != 200) {
            return response()->json(['error' => 'Invalid token'], 401);
        }

        if ($response->failed()) {
            return response()->json(['error' => 'Invalid token'], 401);
        }

        Cache::put($cacheKey, true, now()->addSeconds(90));
        
        return $next($request);
    }
}