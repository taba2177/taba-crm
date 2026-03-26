<?php

namespace Taba\Crm\Http\Controllers;

use Taba\Crm\Models\Post;
use Taba\Crm\Models\PostCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CategoryPreviewController extends Controller
{
    public function category(PostCategory $category)
    {
        // $formData = Cache::get($cacheKey);

        // if (!$formData) {
        //     abort(404, 'Preview data not found for key ' . $cacheKey);
        // }

        // $formData ? $componentView = 'components.homepage.' .  $formData['section_component'] :

       $componentView = 'components.homepage.' . request()->input('data.section_component');

       $posts = Post::with('postCategory')
            ->where('post_category_id', $category->id)
            ->get();

        // $posts = $category->posts()->published()->latest()->get();


        return view('previews.category', [
            'posts' => $posts,
            'componentView' => $componentView
        ]);
    }

}