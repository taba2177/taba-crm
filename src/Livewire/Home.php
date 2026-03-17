<?php

namespace Taba\Crm\Livewire;

use Illuminate\Support\Collection;
use Livewire\Component;
use Spatie\SchemaOrg\Schema;
use Taba\Crm\Models\Post;
use Taba\Crm\Models\PostCategory;
use Illuminate\Support\Str;

class Home extends Component
{
    // Define how many posts a section can have before it's considered "heavy" and lazy-loaded.
    const HEAVY_SECTION_THRESHOLD = 8;

    // This holds the fully loaded data for lazy sections as it comes in.
    public array $loadedSections = [];

    public ?string $metaTitle = null;
    public ?string $metaDescription = null;
    public ?string $seoimage = null;


    public Collection $sections;
    public array $heavySectionsData = [];

 public function mount()
{
    // Only load parent categories (exclude child categories from standalone sections)
    $allSections = PostCategory::whereNotNull('section_component')
        ->parentOnly()
        ->with('firstPost')
        ->withCount(['posts' => function ($query) {
            $query->where("show_in_home", true)->published();
        }])
        ->orderBy('order', 'asc')
        ->get();

    $lightSectionIds = $allSections->filter(function ($section) {
        $isHeavy = $section->posts_count > self::HEAVY_SECTION_THRESHOLD || $section->HEAVY_SECTION;
        return !$isHeavy;
    })->pluck('id');

    if ($lightSectionIds->isNotEmpty()) {
        $sectionsWithPosts = PostCategory::whereIn('id', $lightSectionIds)
            ->with(['posts' => function ($query) {
                $query->where("show_in_home", true)->published()->orderBy('order', 'asc');
            }])
            ->get()
            ->keyBy('id');

        $allSections->each(function ($section) use ($sectionsWithPosts) {
            if (isset($sectionsWithPosts[$section->id])) {
                $section->setRelation('posts', $sectionsWithPosts[$section->id]->posts);
            }
        });
    }

    // Create fake posts for heavy sections
    $allSections->each(function ($section) {
        if ($section->posts_count > self::HEAVY_SECTION_THRESHOLD || $section->HEAVY_SECTION) {
            $fakePosts = collect();

            if ($section->firstPost) {
                for ($i = 0; $i < $section->posts_count; $i++) {
                    $cloned = $section->firstPost->replicate();
                    $cloned->id = "fake-$i";
                    $cloned->title = ['en' => 'loading...', 'ar' => 'تحميل...'];
                    $cloned->slug = "#";
                    $cloned->image_id = 9;
                    $cloned->excerpt = "Loading content...";
                    $cloned->content = [
                    'en' => [
                        ['type' => 'markdown', 'data' => ['content' => 'loading...']],
                        ['type' => 'markdown', 'data' => ['content' => 'loading...']]
                    ],
                    'ar' => [
                        ['type' => 'markdown', 'data' => ['content' => 'جاري التحميل...']],
                        ['type' => 'markdown', 'data' => ['content' => 'جاري التحميل...']]
                    ]
                ];
                    $fakePosts->push($cloned);
                }
            }
            $section->setRelation('posts', $fakePosts);
        }
    });

    $this->sections = $allSections;

    // Prepare SEO data from actual content
    $this->prepareInitialSeoData();
    }

    public function loadRemainingHeavyPosts()
    {
        // Step 5: Load actual posts for heavy sections asynchronously.
        $heavySectionIds = $this->sections->filter(function ($section) {
            return $section->posts_count > self::HEAVY_SECTION_THRESHOLD || $section->HEAVY_SECTION;
        })->pluck('id');

        if ($heavySectionIds->isEmpty()) {
            return;
        }

        $heavySections = PostCategory::with(['posts' => function ($query) {
            $query->where("show_in_home", true)->published()->orderBy('order', 'asc');
        }])
            ->whereIn('id', $heavySectionIds)
            ->get()
            ->keyBy('id');

            $this->sections = $this->sections->map(function ($section) use ($heavySections) {
                if (isset($heavySections[$section->id])) {
                    $section->setRelation('posts', $heavySections[$section->id]->posts);
                }
                return $section;
            });
        // $this->heavySectionsData = $heavySections->toArray();
    }

    public function render()
    {
        $this->setSeoMetadata();
        return view('crm::livewire.home')->layout('crm::components.layouts.app');
    }

    // ... your prepareInitialSeoData, setSeoMetadata, title, and desc methods remain here ...
    // NOTE: I've made your SEO methods more robust to handle cases where data might not be loaded yet.
    public function prepareInitialSeoData()
    {
        // Get the first published post from parent categories only for SEO data
        $seoPost = Post::where("show_in_home", true)
            ->published()
            ->excludeChildCategories()
            ->select('title', 'meta_title', 'meta_description', 'content', 'image_id')
            ->orderBy('order', 'asc')
            ->first();

        if ($seoPost) {
            $this->seoimage = $seoPost->image?->url;
            $this->metaTitle = $seoPost->meta_title ?: $seoPost->title;
            $this->metaDescription = $seoPost->meta_description ?: Str::limit(strip_tags($seoPost->content), 155);
        } else {
            // Fallback: use first category if no posts available
            $firstCategory = $this->sections->first();
            if ($firstCategory) {
                $this->metaTitle = $firstCategory->name;
                $this->metaDescription = $firstCategory->description;
            } else {
                // Final fallback
                $this->metaTitle = config('app.name');
                $this->metaDescription = __('crm::forms.seo.default_description', ['name' => config('app.name')]);
            }
        }
    }

    protected function setSeoMetadata()
    {
        // Get business info from database settings with config fallback
        $businessName = crm_business('name');

        // Use dynamic contact info from database
        $phone = crm_contact('phone');
        $address = crm_contact('address');
        $city = crm_contact('city');
        $postalCode = crm_contact('postal_code');
        $latitude = crm_contact('latitude');
        $longitude = crm_contact('longitude');
        $socialLinks = crm_social_links();

        // Business settings
        $priceRange = crm_business('price_range');
        $opens = crm_business('opens');
        $closes = crm_business('closes');

        seo()
            ->title($this->title())
            ->description($this->desc())
            ->canonical(route('home'))
            ->addSchema(
                Schema::localBusiness()
                    ->name($businessName)
                    ->url(config('app.url'))
                    ->image($this->seoimage)
                    ->telephone($phone)
                    ->priceRange($priceRange)
                    ->contactPoint(
                        Schema::contactPoint()
                            ->telephone($phone)
                            ->contactType('customer service')
                            ->areaServed('SA')
                            ->availableLanguage(['ar', 'en'])
                    )
                    ->geo(
                        Schema::geoCoordinates()
                            ->latitude($latitude)
                            ->longitude($longitude)
                    )
                    ->address(
                        Schema::postalAddress()
                            ->streetAddress($address)
                            ->addressLocality($city)
                            ->addressRegion($city)
                            ->postalCode($postalCode)
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
                            ->opens($opens)
                            ->closes($closes)
                    )
                    ->sameAs($socialLinks)
            );

    }

    public function title(): string
    {
        $baseTitle = $this->metaTitle;
        $fullSuffix = '';
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
        $excerpt = $this->metaDescription;
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