<?php
// src/Http/Middleware/AddDiscoveryHeaders.php

namespace Taba\Crm\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AddDiscoveryHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $links = [
            '<' . url('/sitemap.xml') . '>; rel="sitemap"',
            '<' . url('/llms.txt') . '>; rel="describedby"',
            '<' . url('/api/v1') . '>; rel="service"',
        ];

        $response->headers->set('Link', implode(', ', $links));
        $response->headers->set('X-Api-Catalog', url('/api/v1'));

        return $response;
    }
}
