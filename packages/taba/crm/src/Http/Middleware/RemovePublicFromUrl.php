<?php

namespace Taba\Crm\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RemovePublicFromUrl
{
    public function handle(Request $request, Closure $next)
    {
        $uri = $request->getRequestUri();

        if (str_contains($uri, '/public/')) {
            $newUri = str_replace('/public/', '/', $uri);

            if ($newUri !== $uri) {
                $fullUrl = $request->getSchemeAndHttpHost() . $newUri;
                return redirect($fullUrl, 301);
            }
        }

        return $next($request);
    }
}