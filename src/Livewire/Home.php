<?php

namespace Taba\Crm\Livewire;

use Livewire\Component;
use Spatie\SchemaOrg\Schema;
use Taba\Crm\Models\PostCategory;

class Home extends Component
{
    public $sections;
    public $metaTitle;
    public $metaDescription;


    public function mount()
    {

        $this->sections = PostCategory::with(['posts' => function ($query) {
            $query->where("show_in_home",true)->published()->orderBy('order');
        }])
        ->whereNotNull('section_component') // Only fetch categories with a defined section_component
        ->orderBy('order')
        ->get();

        // 1. Get the post object ONCE to avoid multiple database queries.
        $firstPost = $this->sections[1]->posts()->first();

        if ($firstPost) {
            // 2. Use a ternary operator or if-statement which checks for "falsy" values.
            // The expression ($firstPost->meta_title) will evaluate to false if it's '', null, 0, or false.
            $this->metaTitle = $firstPost->meta_title ? $firstPost->meta_title : $firstPost->title;

            // A similar robust check for the description
            $contentBlock = $firstPost->blocks[0]->data->content ?? $this->sections[0]->posts()->first()->blocks[0]->data->content ?? null;
            $this->metaDescription = $firstPost->meta_description ? $firstPost->meta_description : $contentBlock;
        } else {
            // Handle the case where there is no post at all
            $this->metaTitle = 'مكتب جديان للحلول الهندسية';
            $this->metaDescription = 'مكتب جديان للحلول الهندسية هو مكتب هندسي افتراضي يقدم تصاميم، حسابات، حصر كميات، وخدمات المكتب الفني المساندة لأنظمة الكهرباء والميكانيكا بجودة عالية وسعر منافس. نخدم المؤسسات، شركات المقاولات، مكاتب التصميم والاستشارات الهندسية، وندعمهم خلال ضغط العمل ونرفع معايير الالتزام عبر مهندسين محترفين وفق الكود السعودي والعالمي';
        }

        // Now you can safely dd() the result
        // dd($this->sections[1]->posts()->first());
        // dd($this->metaTitle,$this->metaDescription);
    }

    public function render()
    {
        // Set SEO metadata
        $this->setSeoMetadata();

        return view('livewire.home', [
            'sections' => $this->sections,
        ])->layout('components.layouts.app');
    }

    protected function setSeoMetadata()
    {
        seo()
            ->title($this->title())
            ->description($this->desc())
            ->canonical(route('home'))
            ->addSchema(
                Schema::localBusiness()
                    ->name('مكتب جديان للحلول الهندسية')
                    ->url('https://jedianengineering.com/') // استبدل بالرابط الفعلي إذا توفر
                    ->image($this->sections[0]->image?->url) // استبدل برابط الشعار الفعلي إذا توفر
                    ->telephone('+966583097425') // استبدل برقم الهاتف الفعلي
                    ->priceRange('SAR 500 - SAR 20000') // عدل النطاق السعري حسب الحاجة
                    ->contactPoint(
                        Schema::contactPoint()
                            ->telephone('+966583097425') // استبدل برقم الهاتف الفعلي
                            ->contactType('customer service')
                            ->areaServed('SA')
                            ->availableLanguage(['ar','en'])
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

    public function title(): string
    {
        $baseTitle = $this->metaTitle;
        $fullSuffix = ' مكتب جديان للحلول الهندسية | خدمات هندسية احترافية ومرنة';
        $baseLength = mb_strlen($baseTitle, 'UTF-8');
        $fullSuffixLength = mb_strlen($fullSuffix, 'UTF-8');

        // Case 1: Entire title + full suffix fits
        if ($baseLength + $fullSuffixLength <= 60) {
            return $baseTitle . $fullSuffix;
        }

        // Case 2: Just base title fits completely
        if ($baseLength <= 60) {
            // Find how much suffix we can include
            $availableSpace = 60 - $baseLength;

            // Only include suffix if we can show at least one complete word
            if ($availableSpace >= 7) { // Length of shortest word in suffix
                // Find the last space before the cutoff
                $partialSuffix = mb_substr($fullSuffix, 0, $availableSpace, 'UTF-8');
                $lastSpace = mb_strrpos($partialSuffix, ' ', 0, 'UTF-8');

                return $lastSpace !== false
                    ? $baseTitle . mb_substr($fullSuffix, 0, $lastSpace, 'UTF-8')
                    : $baseTitle;
            }

            return $baseTitle;
        }

        // Case 3: Base title needs trimming
        $trimmed = mb_substr($baseTitle, 0, 60, 'UTF-8');
        $lastSpace = mb_strrpos($trimmed, ' ', 0, 'UTF-8');

        return $lastSpace !== false
            ? mb_substr($trimmed, 0, $lastSpace, 'UTF-8')
            : $trimmed;
    }

    public function desc(): string
    {
        $excerpt = $this->metaDescription . '  مكتب جديان للحلول الهندسية هو مكتب هندسي يقدم تصاميم، حسابات، حصر كميات، وخدمات المكتب الفني المساندة لأنظمة الكهرباء والميكانيكا بجودة عالية وسعر منافس. نخدم المؤسسات، شركات المقاولات، مكاتب التصميم والاستشارات الهندسية، وندعمهم خلال ضغط العمل ونرفع معايير الالتزام عبر مهندسين محترفين وفق الكود السعودي والعالمي ';
        $idealLength = 150; // Optimal for meta descriptions
        $tolerance = 5; // ±5 characters flexibility
        $minLength = $idealLength - $tolerance;
        $maxLength = $idealLength + $tolerance;

        // If already within ideal range
        $currentLength = mb_strlen($excerpt, 'UTF-8');
        if ($currentLength <= $maxLength) {
            return $excerpt;
        }

        // Start with a safe initial cut (longer than needed)
        $initialCut = min($maxLength + 30, $currentLength);
        $workingText = mb_substr($excerpt, 0, $initialCut, 'UTF-8');

        // Arabic-specific break points in priority order
        $breakPoints = [
            '. ',   // Sentence end
            '، ',   // Arabic comma
            '؛ ',   // Arabic semicolon
            ' - ',  // Dash
            ' ',    // Word boundary
        ];

        // Try to find the best break point
        foreach ($breakPoints as $breakPoint) {
            $pos = $this->findOptimalBreak($workingText, $breakPoint, $minLength, $maxLength);

            if ($pos !== false) {
                $cleanCut = mb_substr($excerpt, 0, $pos, 'UTF-8');

                // Ensure we're not leaving just 1-2 words after cut
                $remainingText = mb_substr($excerpt, $pos, null, 'UTF-8');
                $remainingWords = count(array_filter(explode(' ', $remainingText)));

                if ($remainingWords > 1 || mb_strlen($remainingText, 'UTF-8') > 10) {
                    return rtrim($cleanCut) ;
                }
            }
        }

        // Final fallback - cut at last complete word before max length
        $lastSpace = mb_strrpos(mb_substr($excerpt, 0, $maxLength, 'UTF-8'), ' ', 0, 'UTF-8');
        if ($lastSpace !== false && $lastSpace >= $minLength) {
            return mb_substr($excerpt, 0, $lastSpace, 'UTF-8') ;
        }

        // Absolute fallback - hard cut with ellipsis
        return mb_substr($excerpt, 0, $idealLength - 3, 'UTF-8') ;
    }

    private function findOptimalBreak(string $text, string $breakPoint, int $min, int $max): int|false
    {
        $pos = mb_strrpos($text, $breakPoint, 0, 'UTF-8');

        // Find the last break point within our desired range
        while ($pos !== false) {
            if ($pos >= $min && $pos <= $max) {
                return $pos + mb_strlen($breakPoint, 'UTF-8');
            }

            if ($pos < $min) {
                return false;
            }

            // Look for earlier occurrence
            $text = mb_substr($text, 0, $pos, 'UTF-8');
            $pos = mb_strrpos($text, $breakPoint, 0, 'UTF-8');
        }

        return false;
    }
}