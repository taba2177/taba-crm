<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostCategory;

class PostController extends Controller
{

    public function index($categorySlug)
    {
        // get all categories from cache
        $categories = PostCategory::RegisterInHeader();
        $category = PostCategory::where('slug', $categorySlug)->firstOrFail();
        $posts = Post::published()->with('postCategory')->where('post_category_id', $category->id)->latest()->get();

        return view('posts.index', [
            'category' => $category,
            'posts' => $posts,
            'categories' => $categories,
            'layout' => 'layouts.main'
        ]);
    }
    
    public function category(PostCategory $category)
    {
        $posts = $category->posts()->published()->latest()->get();

        return view('posts.category', [
            'category' => $category,
            'posts' => $posts,
            'layout' => 'layouts.main'
        ]);
    }

    public function show(PostCategory $category, Post $post)
    {
        abort_unless($post->post_category_id === $category->id, 404);
        abort_unless($post->is_published || auth()->check(), 404);

        $view = "livewire.post.{$category->order}.show";

        if (!view()->exists($view)) {
            $view = "livewire.post.default.show";
        }

        return view($view, [
            'post' => $post,
            'relatedPosts' => $this->getRelatedPosts($post),
            'layout' => 'layouts.main'
        ]);
    }

    // public function show($category, Post $post)
    // {
    //     abort_unless($post->postCategory->slug === $category, 404);
    //     abort_unless($post->is_published || auth()->check(), 404);

    //     $view = "livewire.post.{$post->postCategory->slug}.show";


    //     return view($view, [
    //         'post' => $post,
    //         'relatedPosts' => $this->getRelatedPosts($post),
    //         'layout' => 'layouts.main'
    //     ]);
    // }

    protected function getRelatedPosts(Post $post)
    {
        return \Taba\Crm\Models\Post::published()
            ->forCategory($post->postCategory->slug)
            ->where('id', '!=', $post->id)
            ->latest()
            ->take(3)
            ->get();
    }
}