<?php

namespace Taba\Crm\Livewire;

use Taba\Crm\Models\Post;
use Taba\Crm\Models\ProductCategory;
use Taba\Crm\Models\review;
use Livewire\Component;
use Spatie\SchemaOrg\Schema;
use Taba\Crm\Models\PostCategory;

class Home extends Component
{
    public $sections;

    public function mount()
    {
        $this->sections = PostCategory::with(['posts' => function ($query) {
            $query->published()->latest();
        }])
        ->whereNotNull('section_component') // Only fetch categories with a defined section_component
        ->orderBy('order')
        ->get();
    }

    public function render()
    {
        // Set SEO metadata
        $this->setSeoMetadata();

        return view('crm::livewire.home', [
            'sections' => $this->sections,
        ])->layout('crm::components.layouts.app');
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
            ->title('بطور للتسويق الالكتروني | حلول تسويقية مبتكرة')
            ->description('بطور للتسويق الالكتروني، نقدم حلول تسويقية متكاملة ومبتكرة لتعزيز تواجدك الرقمي وزيادة مبيعاتك.')
            ->canonical(route('home'))
            ->addSchema(
                Schema::localBusiness()
                    ->name('بطور للتسويق الالكتروني')
                    ->url('https://btoor.com') // Replace with the actual URL
                    ->image('https://btoor.com/assets/images/floating_logo.png') // Replace with the actual logo URL
                    ->telephone('+966501234567') // Replace with the actual phone number
                    ->priceRange('SAR 99 - SAR 9999') // Adjust the price range as needed
                    ->contactPoint(
                        Schema::contactPoint()
                            ->telephone('+966501234567') // Replace with the actual phone number
                            ->contactType('customer service')
                            ->areaServed('SA')
                            ->availableLanguage(['ar'])
                    )
                    ->geo(
                        Schema::geoCoordinates()
                            ->latitude('24.774265') // Replace with the actual latitude
                            ->longitude('46.738586') // Replace with the actual longitude
                    )
                    ->address(
                        Schema::postalAddress()
                            ->streetAddress('شارع example، حي example') // Replace with the actual street address
                            ->addressLocality('الرياض')
                            ->addressRegion('الرياض')
                            ->postalCode('12345') // Replace with the actual postal code
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
                            ->closes('17:00')
                    )
                    ->sameAs([
                        'https://www.facebook.com/btoor', // Replace with the actual Facebook page
                        'https://twitter.com/btoor', // Replace with the actual Twitter page
                        'https://www.instagram.com/btoor', // Replace with the actual Instagram page
                        'https://www.linkedin.com/company/btoor', // Replace with the actual LinkedIn page
                        //'https://www.youtube.com/channel/...' // Replace with the actual YouTube channel if available
                    ])
            );
    }
}
