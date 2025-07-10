<?php

namespace App\Livewire;

use App\Models\review;
use Livewire\Component;
use Spatie\SchemaOrg\Schema;

class Services extends Component
{

    public $reviews;


    public function render()
    {
        $this->setSeoMetadata();

        $this->reviews = \Taba\Crm\Models\review::where('rating', 5)->get()->take(20);

        return view('livewire.services', [
            'reviews' => $this->reviews,
        ]);
    }
    protected function setSeoMetadata()

    {
        seo()
            ->title('افضل خدمات تركيب وتفصيل الستائر من محلات صن رول للستائر')
            ->description('نقدم خدمات احترافية في تركيب وتفصيل جميع أنواع الستائر - ستائر رول، ستائر امريكي، ستائر للمكاتب والمستشفيات - مع ضمان الجودة وأفضل الأسعار')
            ->canonical(route('services'))
            ->addSchema(
            Schema::webPage()
                ->name(config('app.name'))
                ->description('نقدم خدمات احترافية في تركيب وتفصيل جميع أنواع الستائر - ستائر رول، ستائر امريكي، ستائر للمكاتب والمستشفيات - مع ضمان الجودة وأفضل الأسعار')
                ->url(route('services'))
                ->author(Schema::organization()->name(config('app.name')))
            )
            ->addSchema(
                Schema::videoObject()
                    ->name("تعرف على خدمات صن رول للستائر")
                    ->description("فيديو تعريفي عن خدمات صن رول للستائر، بما في ذلك ستائر الرول، الستائر الأمريكية، والستائر الشيفون.")
                    ->thumbnailUrl("https://sunroll.com.sa/images/video-thumbnail.jpg")
                    ->uploadDate("2025-03-21T00:00:00+03:00")
                    ->duration("PT1M30S")
                    ->contentUrl("https://www.youtube.com/embed/CbAZ6w87ZGM")
                    ->embedUrl("https://www.youtube.com/embed/CbAZ6w87ZGM")
                    ->publisher(
                        Schema::organization()
                            ->name("Sun Roll")
                            ->logo(
                                Schema::imageObject()
                                    ->url("https://sunroll.com.sa/images/logo.jpg")
                                    ->width(600)
                                    ->height(60)
                            )
                    )
            );
    }

}
