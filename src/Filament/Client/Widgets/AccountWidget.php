<?php

namespace App\Filament\Widgets;

class AccountWidget extends \Filament\Widgets\AccountWidget
{
    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 3;
}