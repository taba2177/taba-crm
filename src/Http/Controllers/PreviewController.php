<?php

namespace Taba\Crm\Http\Controllers;

use Taba\Crm\Models\Post;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class PreviewController extends Controller
{
    public function post(Post $post)
    {
        $key = 'post_' . Str::random(12);
        $data = request()->input('data', []);

        Cache::put("preview:{$key}", [
            'type' => 'post',
            'id'   => $post->id,
            'data' => $data,
        ], now()->addMinutes(10));

        $sectionComponent = $post->postCategory?->section_component ?? '';

        return redirect("/?_preview={$key}&_section=" . urlencode($sectionComponent));
    }
}
