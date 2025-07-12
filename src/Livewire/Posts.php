<?php

namespace Taba\Crm\Livewire;

use Taba\Crm\Models\Post;
use Livewire\Component;
use Spatie\SchemaOrg\Schema;

class Posts extends Component
{
    public function show(Post $post)
    {
        $relatedPosts = \Taba\Crm\Models\Post::with('postCategory')->published()->where('id', '!=', $post->id)
            ->latest()
            ->where('slug', 'not like', '%alshot-oalahkam%')
            ->take(3)
            ->get();


            // if ($post->postCategory->slug === 'page')
            // {
            //     return view('livewire.page', compact('post', 'relatedPosts'));
            // }

        return view('livewire.post.show', compact('post', 'relatedPosts'));
    }

    public function render()
    {
        // seo()
        //     ->title($title = config('app.name') .' | '. 'تصنيع تفصيل ستائر جاهزة لكل المساحات - صن رول للستائر')
        //     ->description($description = ' افضل الستائر و الخامات بافضل أسعار ستائر غرف النوم رول وامريكي مقاومة للحرار اين تجد ستائر لمكتبك -افضل خيار المستشبيات - كيف اختار ستائر المنزل')
        //     ->canonical($url = route('posts'))
        //     ->addSchema(
        //         Schema::webPage()
        //             ->name($title .' - '. $description)
        //             ->description($description)
        //             ->url($url)
        //             ->author(Schema::organization()->name($title))
        //     );
        if (!request()->routeIs('home')) {
        $this->setSeoMetadata();
         $posts = \Taba\Crm\Models\Post::with('postCategory')->published()
            ->latest('published_at')
            ->where('slug', 'not like', '%alshot-oalahkam%')
            ->get(); // Execute the query and get the results
        }else{
            $posts = \Taba\Crm\Models\Post::with('postCategory')->published()
            ->latest('published_at')
            ->where('slug', 'not like', '%alshot-oalahkam%')
            ->take(3)->get();
        }

        return view('livewire.posts' , compact('posts'));
    }

    protected function setSeoMetadata()

    {
        seo()
            ->title('صن رول : الحلول الذكية لستائر منزلية وعصرية تناسب احتياجاتك')
            ->description('ستائر رول شيفون وايضا امريكي وويفي من صن رول، الأفضل لمن يبحث عن أناقة عصرية وراحة في منزلك أو مكتبك')
            ->canonical(route('posts'))
            ->addSchema(
            Schema::webPage()
                ->name('مقالات ونصائح عن الستائر - '.config('app.name'))
                ->description('مجموعة من المقالات المتخصصة في عالم الستائر والديكور، نقدم نصائح وإرشادات لاختيار وتركيب وصيانة الستائر بمختلف أنواعها')
                ->url(route('posts'))
                ->author(Schema::organization()->name(config('app.name')))
            );
        // seo()
        //     ->title('تصنيع تفصيل ستائر جاهزة ستائر رول | صن رول للستائر')
        //     //config('app.name'))
        //     ->description('اشتر افضل الستائر و الخامات بافضل أسعار ستائر غرف النوم رول وامريكي مقاومة للحرار اين تجد ستائر لمكتبك - افضل خيار المستشفيات - كيف اختار ستائر المنزل')
        //     ->canonical(route('gallery-images', 'all'))
        //     ->addSchema(
        //         Schema::webPage()
        //             ->name(config('app.name'))
        //             ->description('اشتر افضل الستائر و الخامات بافضل أسعار ستائر غرف النوم رول وامريكي مقاومة للحرار اين تجد ستائر لمكتبك - افضل خيار المستشبيات - كيف اختار ستائر المنزل')
        //             ->url(route('services'))
        //             ->author(Schema::organization()->name(config('app.name')))
        //     );
    }
}