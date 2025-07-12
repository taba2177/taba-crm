<?php

use Taba\Crm\Http\Controllers\CategoryPreviewController;
use Illuminate\Support\Facades\Route;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use Taba\Crm\Http\Controllers\GoogleTranslateController;
use Taba\Crm\Livewire\Post\Show as PostShow;
use Taba\Crm\Livewire\Posts;
use Taba\Crm\livewire\Home;
use Taba\Crm\Http\Controllers\PageController;
use Taba\Crm\Http\Controllers\PostController;
use Taba\Crm\Http\Controllers\PreviewController;
use Taba\Crm\Models\Page;
use Taba\Crm\Models\PostCategory;

Route::get('/preview/post/{post:slug}', [PreviewController::class, 'post'])->name('preview.post');
Route::get('/preview/category/{category:slug}', [CategoryPreviewController::class, 'category'])->name('preview.category');

Route::get('/', Home::class)->name('home');

// Route::get('/contact', [PageController::class, 'contact'])->name('page.contact');

// lang.switch'
Route::get('/lang/change/{lang}', function ($lang) {
    app()->setLocale($lang);
    session(['locale' => $lang]);
    return redirect()->back();
})->name('lang.switch');

Route::get('/{slug}', [PageController::class, 'show'])->name('page.show');

Route::get('/{slug}', function ($slug) {
    // Check if the slug matches a Page
    if (Page::where('slug', $slug)->exists()) {
        return app(PageController::class)->show($slug);
    }
    // Check if the slug matches a Post Category
    elseif (PostCategory::where('slug', $slug)->exists()) {
        return app(PostController::class)->index($slug);
    }
    // If neither, return 404
    // abort(404);
})->name('dynamic.route');

// Route for individual posts (e.g., /category-slug/post-slug)
Route::get('/{category}/{post:slug}', [PostController::class, 'show'])
    ->name('posts.show');


Route::get('/sitemap', function () {
    // Create sitemap instance
    $sitemap = Sitemap::create();

    // Add homepage
    $sitemap->add(Url::create('/')
        ->setLastModificationDate(now())
        ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
        ->setPriority(1.0));

    // Add static pages (from your page.show route)
    Page::where('is_published', true)->each(function ($page) use ($sitemap) {
        $sitemap->add(Url::create(route('page.show', ['slug' => $page->slug]))
            ->setLastModificationDate($page->updated_at)
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
            ->setPriority(0.7));
    });

    // Add post categories (from your dynamic route)
    PostCategory::where('is_active', true)->each(function ($category) use ($sitemap) {
        $sitemap->add(Url::create("/{$category->slug}")
            ->setLastModificationDate($category->updated_at)
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
            ->setPriority(0.6));
    });

    // Add individual posts (from posts.show route)
    Post::with('category')
        ->where('is_published', true)
        ->each(function ($post) use ($sitemap) {
            $sitemap->add(Url::create(route('posts.show', [
                'category' => $post->category->slug,
                'post' => $post->slug
            ]))
                ->setLastModificationDate($post->updated_at)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                ->setPriority(0.9));
        });

    // Add other static routes if needed
    $staticRoutes = ['about', 'contact', 'services', 'blog'];
    foreach ($staticRoutes as $route) {
        $sitemap->add(Url::create("/$route")
            ->setLastModificationDate(now())
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
            ->setPriority(0.8));
    }

    // Write to file
    $sitemap->writeToFile(public_path('sitemap.xml'));

    return response()->file(public_path('sitemap.xml'));
})->name('sitemap');

Route::get('/homepage-section-preview', function () {
    $component = request('component');
    $postCategoryId = request('post_category_id');

    $postCategory = null;
    if ($postCategoryId) {
        $postCategory = \Taba\Crm\Models\PostCategory::find($postCategoryId);
    }

    return view($component, [
        'posts' => $postCategory ? $postCategory->posts : collect(),
    ]);
})->name('homepage-section-preview');