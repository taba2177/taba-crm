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

        Cache::put("preview:{$key}", [
            'type' => 'category',
            'id'   => $category->id,
            'data' => request()->input('data', []),
        ], now()->addMinutes(10));

        return redirect('/?_preview=' . $key);
    }
}
