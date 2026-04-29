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

        $links = ['<' . url('/api/v1') . '>; rel="service"'];

        if (file_exists(public_path('sitemap.xml'))) {
            array_unshift($links, '<' . url('/sitemap.xml') . '>; rel="sitemap"');
        }
        if (file_exists(public_path('llms.txt'))) {
            $links[] = '<' . url('/llms.txt') . '>; rel="describedby"';
        }

        $response->headers->set('Link', implode(', ', $links));
        $response->headers->set('X-Api-Catalog', url('/api/v1'));

        return $response;
    }
}
