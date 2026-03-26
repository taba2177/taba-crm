<?php

namespace Taba\Crm\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Set the application locale from the Accept-Language header.
 * This enables Angular to control the locale per-request via:
 *   headers: { 'Accept-Language': 'ar' }
 */
class SetLocaleFromHeader
{
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->header('Accept-Language');

        if ($locale) {
            // Extract primary language tag (e.g., "ar" from "ar-SA,ar;q=0.9")
            $locale = explode(',', $locale)[0];
            $locale = explode('-', $locale)[0];
            $locale = trim($locale);

            $available = config('crm.available_locales', ['ar', 'en']);

            if (in_array($locale, $available)) {
                app()->setLocale($locale);
            }
        }

        return $next($request);
    }
}
