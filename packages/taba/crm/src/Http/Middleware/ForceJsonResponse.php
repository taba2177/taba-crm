<?php

namespace Taba\Crm\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Force JSON responses for all API requests.
 * Ensures Laravel always returns JSON (not HTML redirects) for API routes,
 * which is critical for Angular HttpClient.
 */
class ForceJsonResponse
{
    public function handle(Request $request, Closure $next): Response
    {
        // Force Accept header to JSON so Laravel returns JSON errors
        $request->headers->set('Accept', 'application/json');

        return $next($request);
    }
}
