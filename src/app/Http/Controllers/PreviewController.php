<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostCategory;
use Illuminate\Http\Request;

class PreviewController extends Controller
{
    public function post(Post $post)
    {
        if (view()->exists('components.homepage.' . request()->input('data.homepage_section_component'))) {
            $componentView = 'components.homepage.' . request()->input('data.homepage_section_component');
            $posts = collect([$post]);

            return view('previews.category', [
                'posts' => $posts,
                'componentView' => $componentView
            ]);
        } else {
            $componentView = 'livewire.post.templates.' . request()->input('data.homepage_section_component');
            $relatedPosts = $post->relatedPosts()->published()->latest()->take(4)->get();

            // $posts = PostCategory::with(['posts' => function ($query) {
            //     $query->published()->latest();
            // }])
            //     ->whereNotNull('section_component') // Only fetch categories with a defined section_component
            //     ->orderBy('order')
            //     ->get();

            $posts = collect([$post]);
        }

        dd($componentView);
        // $relatedPosts = $post->relatedPosts()->published()->latest()->take(4)->get();

        return view('previews.post', [
            'post' => $post,
            'componentView' => $componentView,
            'relatedPosts' => $relatedPosts,
        ]);
    }
}