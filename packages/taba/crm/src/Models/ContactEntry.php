<?php

namespace Taba\Crm\Models;

use Illuminate\Database\Eloquent\Model;

class ContactEntry extends Model
{
    protected $fillable = [
        'email',
        'message',
        'name',
    ];
}