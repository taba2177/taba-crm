<?php

namespace App\Livewire;

use Livewire\Component;
use Spatie\SchemaOrg\Schema;


class About extends Component
{

    /**
     * Set SEO metadata for the page.
     */

    public function render()
    {
        $this->setSeoMetadata();

        return view('livewire.about');
    }

    protected function setSeoMetadata()

    {
        seo()
            ->title('من نحن | صن رول للستائر - خبراء في تصنيع وتركيب الستائر')
            ->description('تعرف على صن رول للستائر - شركة رائدة في مجال تصنيع وتركيب الستائر بجميع أنواعها. نقدم خدمات احترافية وحلول مبتكرة لعملائنا منذ سنوات')
            ->canonical(route('about'))
            ->addSchema(
            Schema::webPage()
                ->name('من نحن | صن رول للستائر والكنب')
                ->description('صن رول للستائر - متخصصون في تصنيع وتركيب الستائر بجميع أنواعها. نوفر خدمات متكاملة للمنازل والشركات والمستشفيات بأعلى جودة وأفضل الأسعار')
                ->url(route('about'))
                ->author(Schema::organization()->name(config('app.name')))
            );
    }
}
