<?php

namespace Taba\Crm\Http\Middleware;

use Closure;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentColor;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Taba\Crm\Models\CrmSetting;

class ApplyBrandColors
{
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $primary = CrmSetting::get('crm_brand_primary_color', config('crm.brand.primary_color', '#ecf163'));
            $secondary = CrmSetting::get('crm_brand_secondary_color', config('crm.brand.secondary_color', '#1f201c'));

            FilamentColor::register([
                'primary' => Color::hex($primary),
                'gray' => Color::hex($secondary),
            ]);
        } catch (\Throwable) {
            // DB not yet migrated — skip silently
        }

        return $next($request);
    }
}