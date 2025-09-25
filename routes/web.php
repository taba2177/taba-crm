<?php

use Taba\Crm\Http\Controllers\CategoryPreviewController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use Taba\Crm\Http\Controllers\GoogleTranslateController;
use Taba\Crm\Livewire\Post\Show as PostShow;
use Taba\Crm\Livewire\Posts;
use Taba\Crm\Livewire\Home;
use Taba\Crm\Http\Controllers\PageController;
use Taba\Crm\Http\Controllers\PostController;
use Taba\Crm\Http\Controllers\PreviewController;
use Taba\Crm\Models\Page;
use Taba\Crm\Models\Post;
use Taba\Crm\Models\PostCategory;


Route::middleware('web')->group(function () {

Route::get('/preview/post/{post}', [PreviewController::class, 'post'])->name('preview.post');
Route::get('/preview/category/{category}', [CategoryPreviewController::class, 'category'])->name('preview.category');

Route::get('/', Home::class)->name('home');

Route::get('/sitemap', function () {
    $sitemap = Sitemap::create();

    // 1. Add the homepage
    $sitemap->add(
        Url::create(route('home'))
            ->setLastModificationDate(now())
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
            ->setPriority(1.0)
    );

    // 2. Add PostCategory pages (using your 'dynamic.route')
    // Fetches only categories that should be public (e.g., shown in the header)
    PostCategory::where('register_in_header', true)->each(function (PostCategory $category) use ($sitemap) {
        $sitemap->add(
            Url::create(route('dynamic.route', ['slug' => $category->slug]))
                ->setLastModificationDate($category->updated_at)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                ->setPriority(0.8)
        );
    });

    // 3. Add individual Post pages (using your 'posts.show' route)
    Post::with('postCategory') // Use the correct relationship name
        ->published()
        ->whereNotNull('homepage_section_component')
        ->each(function (Post $post) use ($sitemap) {
            // Ensure the post has a category to prevent errors
            if ($post->postCategory) {
                $sitemap->add(
                    Url::create(route('posts.show', [
                        'category' => $post->postCategory->slug,
                        'post' => $post->slug
                    ]))
                    ->setLastModificationDate($post->updated_at)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                    ->setPriority(0.7)
                );
            }
        });

    // Write the sitemap to a file
    $sitemap->writeToFile(public_path('sitemap.xml'));

    return response()->file(public_path('sitemap.xml'));
})->name('sitemap');

// lang.switch'
Route::get('/lang/change/{lang}', function ($lang) {
    app()->setLocale($lang);
    Session(['locale' => $lang]);
    return redirect()->back();
})->name('lang.switch');

Route::get('/{slug}', function ($slug) {

    if (PostCategory::where('slug', $slug)->exists()) {
        return app(PostController::class)->index($slug);
    }else{
         return abort(404);
    }
})->name('dynamic.route');

Route::get('/{category}/{post:slug}',function ($category,$post) {
    // dd($post);
    if (Post::where('slug', $post)->firstOrFail()->homepage_section_component) {
        return app(PostController::class)->show($category,$post);
    }else{
         return abort(404);
    } // [PostController::class, 'show'])

    })->name('posts.show');

});


// Route::get('/homepage-section-preview', function () {
//     $component = request('component');
//     $postCategoryId = request('post_category_id');

//     $postCategory = null;
//     if ($postCategoryId) {
//         $postCategory = \Taba\Crm\Models\PostCategory::find($postCategoryId);
//     }

//     return view($component, [
//         'posts' => $postCategory ? $postCategory->posts : collect(),
//     ]);
// })->name('homepage-section-preview');
