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

        Cache::put("preview:{$key}", [
            'type' => 'post',
            'id'   => $post->id,
            'data' => request()->input('data', []),
        ], now()->addMinutes(10));

        return redirect("/?_preview={$key}");
    }
}
