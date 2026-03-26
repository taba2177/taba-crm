<?php

namespace Taba\Crm\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = [
        'name',
        'items',
        'group',
    ];

    protected $casts = [
        'items' => 'array',
    ];
}
