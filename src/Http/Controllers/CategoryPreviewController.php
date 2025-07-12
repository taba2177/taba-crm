<?php

namespace Taba\Crm\Http\Controllers;

use Taba\Crm\Models\Post;
use Taba\Crm\Models\PostCategory;
use Illuminate\Http\Request;

class CategoryPreviewController extends Controller
{
    public function category(PostCategory $category)
    {
        $componentView = 'components.homepage.' . request()->input('data.section_component');

        // $posts = Post::with('postCategory')
        //     ->where('post_category_id', $category->id)
        //     ->get();
        $posts = $category->posts()->published()->latest()->get();


        return view('previews.category', [
            'posts' => $posts,
            'componentView' => $componentView
        ]);
    }

    // public function category(PostCategory $category)
    // {
    //     $componentView = 'components.homepage.' . request()->input('data.section_component');

    //     $posts = $category->posts()->published()->latest()->get();

    //     return view('previews.category', [
    //         'posts' => $posts,
    //         'componentView' => $componentView
    //     ]);
    // }

    // public function show(Request $request, PostCategory $category)
    // {
    //     // You can pass the component view name and props from the preview URL
    //     // For example: /preview/category/{category}?component=homepage.featured-posts
    //     $component = $request->input('component', 'posts.category');

    //     // The data to pass to the component
    //     $data = [
    //         'category' => $category,
    //         'posts' => $category->posts()->published()->latest()->get(),
    //         'layout' => 'layouts.app' // Ensure the layout is set
    //     ];

    //     // Render the component within the main layout
    //     return view('previews.category', [
    //         'component' => $component,
    //         'componentData' => $data,
    //     ]);
    // }
}
