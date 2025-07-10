<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Taba\Crm\Models\Post;

class PageController extends Controller
{
    public function home()
    {
        $featured = Post::featured()->orderBy('published_at', 'desc')->get();

        return view('livewire.home', ['featured' => $featured]);
    }

    public function contact()
    {
        $page = Page::where('slug', 'contact')->firstOrFail();
        return view('page.show-contact', ['page' => $page]);
    }

    public function show($slug)
    {
        $page = Page::where('slug', $slug)->firstOrFail();
        return view('page.show', ['page' => $page]);
    }

    // public function show($slug)
    // {
    //     abort_unless($page = Page::whereSlug($slug)->first(), 404);

    //     return view('page.show', ['page' => $page]);
    // }
}
