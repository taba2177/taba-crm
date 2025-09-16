<?php

namespace Taba\Crm\Livewire;

use Livewire\Component;
use Spatie\SchemaOrg\Schema;
use Taba\Crm\Models\PostCategory;

class Home extends Component
{
    public $sections;

    public function mount()
    {
        $this->sections = PostCategory::with(['posts' => function ($query) {
            $query->where("show_in_home",true)->published()->orderBy('order');
        }])
        ->whereNotNull('section_component') // Only fetch categories with a defined section_component
        ->orderBy('order')
        ->get();
    }

    public function render()
    {
        // Set SEO metadata
        $this->setSeoMetadata();

        return view('livewire.home', [
            'sections' => $this->sections,
        ])->layout('components.layouts.app');
    }

    // public function oddColorText($advertisement)
    // {
    //     $words = explode(' ', $advertisement->title);
    //     $this->formattedTitle = '';

    //     foreach ($words as $index => $word) {
    //         if ($index % 2 === 0) { // Odd-indexed words (0, 2, 4, ...)
    //             $this->formattedTitle .= '<span class="fw-6 txt-orange">' . $word . '</span> ';n    //         } else { // Even-indexed words (1, 3, 5, ...)
    //             $this->formattedTitle .= $word . ' ';
    //         }
    //     }
    //     return $this->formattedTitle;
    // }

    /**
     * Set SEO metadata for the page.
     */
    protected function setSeoMetadata()
    {
        seo()
            ->title('مكتب جديان للحلول الهندسية | خدمات هندسية احترافية ومرنة')
            ->description('مكتب جديان للحلول الهندسية هو مكتب هندسي افتراضي يقدم تصاميم، حسابات، حصر كميات، وخدمات المكتب الفني المساندة لأنظمة الكهرباء والميكانيكا بجودة عالية وسعر منافس. نخدم المؤسسات، شركات المقاولات، مكاتب التصميم والاستشارات الهندسية، وندعمهم خلال ضغط العمل ونرفع معايير الالتزام عبر مهندسين محترفين وفق الكود السعودي والعالمي.')
            ->canonical(route('home'))
            ->addSchema(
                Schema::localBusiness()
                    ->name('مكتب جديان للحلول الهندسية')
                    ->url('https://jadyaan.com') // استبدل بالرابط الفعلي إذا توفر
                    ->image('https://jadyaan.com/assets/images/logo.png') // استبدل برابط الشعار الفعلي إذا توفر
                    ->telephone('+966501234567') // استبدل برقم الهاتف الفعلي
                    ->priceRange('SAR 500 - SAR 20000') // عدل النطاق السعري حسب الحاجة
                    ->contactPoint(
                        Schema::contactPoint()
                            ->telephone('+966501234567') // استبدل برقم الهاتف الفعلي
                            ->contactType('customer service')
                            ->areaServed('SA')
                            ->availableLanguage(['ar'])
                    )
                    ->geo(
                        Schema::geoCoordinates()
                            ->latitude('24.774265') // استبدل بالإحداثيات الفعلية إذا توفر
                            ->longitude('46.738586')
                    )
                    ->address(
                        Schema::postalAddress()
                            ->streetAddress('شارع مثال، حي مثال') // استبدل بالعنوان الفعلي إذا توفر
                            ->addressLocality('الرياض')
                            ->addressRegion('الرياض')
                            ->postalCode('12345') // استبدل بالرمز البريدي الفعلي إذا توفر
                            ->addressCountry('SA')
                    )
                    ->openingHoursSpecification(
                        Schema::openingHoursSpecification()
                            ->dayOfWeek([
                                'Monday',
                                'Tuesday',
                                'Wednesday',
                                'Thursday',
                                'Friday',
                                'Saturday',
                                'Sunday'
                            ])
                            ->opens('09:00')
                            ->closes('18:00')
                    )
                    ->sameAs([
                        // أضف روابط وسائل التواصل الاجتماعي الفعلية إذا توفرت
                        // 'https://www.facebook.com/jadyaan',
                        // 'https://twitter.com/jadyaan',
                        // 'https://www.instagram.com/jadyaan',
                        // 'https://www.linkedin.com/company/jadyaan',
                    ])
            );

    }
}
