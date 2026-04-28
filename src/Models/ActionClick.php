<?php

namespace Taba\Crm\Models;

use Illuminate\Database\Eloquent\Model;

class ActionClick extends Model
{
    protected $fillable = [
        'action',
        'source',
        'page',
        'ip_hash',
    ];
}
