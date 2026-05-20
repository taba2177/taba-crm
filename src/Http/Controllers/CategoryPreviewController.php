<?php

namespace Taba\Crm\Http\Controllers;

use Taba\Crm\Models\PostCategory;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class CategoryPreviewController extends Controller
{
    public function category(PostCategory $category)
    {
        $key = 'cat_' . Str::random(12);
        $data = request()->input('data', []);

        Cache::put("preview:{$key}", [
            'type' => 'category',
            'id'   => $category->id,
            'data' => $data,
        ], now()->addMinutes(10));

        $sectionComponent = $data['section_component'] ?? $category->section_component;

        return redirect('/?_preview=' . $key . '&_section=' . urlencode($sectionComponent));
    }
}
