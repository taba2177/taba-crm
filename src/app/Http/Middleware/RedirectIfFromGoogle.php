<?php

// app/Http/Middleware/RedirectIfFromGoogle.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RedirectIfFromGoogle
{
    public function handle(Request $request, Closure $next)
    {
        $referer = $request->server('HTTP_REFERER');

        if (
            $request->is('contact') &&
            $referer &&
            str_contains($referer, 'google.')
        ) {
            session(['came_from_google' => true]);
        }

        return $next($request);
    }
}
