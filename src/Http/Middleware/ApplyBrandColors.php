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
            $primary  = CrmSetting::get('crm_brand_primary_color', config('crm.brand.primary_color', '#0ea5e9'));
            $grayName = CrmSetting::get('crm_brand_gray_palette', config('crm.brand.gray_palette', 'Slate'));

            $grayConst = strtoupper($grayName[0]) . strtolower(substr($grayName, 1));
            $gray = defined(Color::class . '::' . $grayConst)
                ? constant(Color::class . '::' . $grayConst)
                : Color::Slate;

            FilamentColor::register([
                'primary' => Color::hex($primary),
                'gray'    => $gray,
            ]);
        } catch (\Throwable) {
            // DB not yet migrated — skip silently
        }

        return $next($request);
    }
}