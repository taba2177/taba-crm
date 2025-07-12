<?php

namespace Taba\Crm\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomepageSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'component_view',
        'post_category_id',
        'order',
        'additional_data',
    ];

    protected $casts = [
        'additional_data' => 'array',
    ];

    public function postCategory()
    {
        return $this->belongsTo(PostCategory::class);
    }
}
