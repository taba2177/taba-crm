<?php

namespace Taba\Crm\Filament\Resources;

use Z3d0X\FilamentLogger\Resources\ActivityResource as BaseResource;

class ActivityResource extends BaseResource
{
    /**
     * The resource navigation sort order.
     */
    protected static ?int $navigationSort = 10;
    protected static bool $shouldRegisterNavigation = false;
    /**
     * Get the navigation badge for the resource.
     */
    public static function getNavigationBadge(): ?string
    {
        return number_format(static::getModel()::count());
    }
}
