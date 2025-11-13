<?php

namespace Taba\Crm\Livewire;

use Taba\Crm\Models\Post;
use Livewire\Component;
use Spatie\SchemaOrg\Schema;
use Illuminate\Support\Str;

class Posts extends Component
{
    public $metaTitle;
    public $metaDescription;

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

        -        return view('livewire.post.show', compact('post', 'relatedPosts'));
        +        return view('crm::livewire.post.show', compact('post', 'relatedPosts'));
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

        -        return view('livewire.posts' , compact('posts'));
        +        return view('crm::livewire.posts' , compact('posts'));
    }

    protected function setSeoMetadata()
    {
        if(request()->routeIs('*service*'))
       $Schema = Schema::webPage()
                ->name(config('app.name'))
                ->description($this->post->meta_description ?: Str::limit(strip_tags($this->post->content), 155))
                ->url(route('services'))
                ->author(Schema::organization()->name(config('app.name')));
        else
         $Schema = Schema::service()
                        ->name($this->post->title)
                        ->description($this->post->meta_description ?: Str::limit(strip_tags($this->post->content), 250))
                        ->url($this->post->url)
                        ->image($this->post->image?->url)
                        ->provider(
                            Schema::organization()->name(config('app.name'))
                        )
                        ->areaServed(
                            Schema::place()->name('جدة')
                        )
                        ->category($this->post->postCategory->name);

            seo()
                ->title($this->post->meta_title ?: $this->post->title)
                ->description($this->post->meta_description ?: Str::limit(strip_tags($this->post->content), 155))
                ->canonical($this->post->url)
                ->addSchema($Schema);
    }
}
