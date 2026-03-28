<?php

namespace Taba\Crm\Models;

use Illuminate\Database\Eloquent\Model;

class ContactEntry extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'message',
        'service',
    ];
}
