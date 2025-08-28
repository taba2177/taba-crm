<?php

namespace Taba\Crm\Filament\Clusters;

use Filament\Clusters\Cluster;

// create cluster class
class Posts extends Cluster{

    // navicon
    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    // //navgroup
    // protected static ?string $navigationGroup = null;

    // //sort
    // protected static ?int $navigationSort = 1;
    //     public static function getNavigationGroup(): ?string
    // {
    //     return __('Collections');
    // }
    // public static function getNavigationLabel(): string
    // {
    //     return __('Posts'); // Translate your desired label
    // }

    // public static function getNavigationBadge(): ?string
    // {
    //     return number_format(static::getModel()::count());
    // }
}