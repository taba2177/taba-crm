<?php

namespace App\Livewire;

use App\Models\Color;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Cache;
use Spatie\SchemaOrg\Schema;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SingleItem extends Component
{
    use WithPagination;

    public $category; // Holds category slug from URL
    public $product;  // Holds product slug from URL
    public $productModel; // Loaded Product model instance
    public $products; // Related products in the same category
    public $productId;
    protected $listeners = ['changeProduct' => 'handleProductChange'];
    public $metaTitle, $metaDescription, $metaImage;

    public function mount($category, $product)
    {
        $this->category = $category;
        $this->product = $product;
        $this->loadProduct();
        $this->setMetaTags();
    }

    protected function loadProduct()
    {
        try {
            // Query product based on category and product slugs
            $this->productModel = Product::whereHas('product_category', function ($query) {
                $query->where('url', $this->category);
            })->where('url', $this->product)
                ->with(['product_category.parent', 'fabrics'])
                ->firstOrFail();
            // dd($this->productModel);
            $this->dispatch('productChanged');

            $this->productId = $this->productModel->id;
            $this->products = Product::where('product_category_id', $this->productModel->product_category_id)->get();
        } catch (ModelNotFoundException $e) {
            abort(404, 'Product not found.');
        }
    }

    protected function setMetaTags()
    {
        $this->metaTitle = $this->productModel->title ?? ' ستاير مودرن جاهزة وتفصيل '.$this->productModel->name;

        $this->metaDescription = $this->productModel->meta_description ?? $this->productModel->description ?? 'Explore our premium collection.';

        $this->metaImage = asset('storage/' . ($this->productModel->image[1] ?? $this->productModel->image));
    }

    public function render()
    {
        $this->setSeoMetadata();

        $relatedProducts = Cache::remember('related_products_' . $this->productId, 3600, function () {
            return Product::where('id', '!=', $this->productId)
                ->inRandomOrder()
                ->paginate(8);
        });
        // dd($this->productModel);

        return view('livewire.single-item', [
            'products' => $this->products,
            'currentProduct' => $this->productModel,
            'relatedProducts' => $relatedProducts,
        ]);
    }

    protected function setSeoMetadata()
    {
        seo()
            ->title($this->title())
            ->description($this->desc())
            ->canonical(route('curtains', ['category' => $this->category, 'product' => $this->product]))
            ->image($this->metaImage)
            ->addSchema(
                Schema::Product()
                    ->name($this->productModel->name)
                    ->description($this->productModel->description ?? 'مجموعة من الستائر الرائعة بتصاميم عصرية.')
                    ->image(asset('storage/' . ($this->productModel->image[0] ?? $this->productModel->image)))
                        //array_map(fn($image) => asset('storage/' . $image[0]), $this->productModel->image ?? []))
                    ->brand(Schema::Brand()->name('SunRoll'))
                    ->sku($this->productModel->sku ?? 'SR-Combo')
                    ->offers(
                        Schema::Offer()
                            ->url(url('/contact'))
                            ->priceCurrency('SAR')
                            ->hasMerchantReturnPolicy(
                                Schema::MerchantReturnPolicy()
                                    ->returnPolicyCategory('https://schema.org/MerchantReturnFiniteReturnWindow')
                                    ->returnPolicySeasonalOverride('https://schema.org/AllYear')
                                    ->returnPolicyMinLength('P14D')
                                    ->returnPolicyMaxLength('P30D')
                                    ->returnPolicyLabel('سياسة الإرجاع : يمكنك إرجاع المنتج في حالة وجود عيب في التصنيع')
                                    ->returnPolicyURL(url('/blogs/alshot-oalahkam'))
                                    ->applicableCountry('SA')
                                    ->returnShippingFeesAmount(
                                        Schema::MonetaryAmount()
                                            ->value(0)
                                            ->currency('SAR')
                                    )
                                    ->returnShippingFeesAmountCurrency('SAR')
                                    ->returnShippingFeesAmountLabel('تتحمل صن رول تكاليف الشحن في حالة وجود عيب في التصنيع')
                                    ->returnShippingFeesAmountCurrencyLabel('SAR')
                                    ->returnmethod('https://schema.org/ReturnByMail')
                                    ->merchantReturnDays(5)
                                    ->merchantReturnDaysLabel('تستطيع إرجاع المنتج خلال 5 أيام من استلامه')
                                    ->merchantReturnDaysURL(url('/blogs/alshot-oalahkam'))
                                    ->returnFees('https://schema.org/FreeReturn')
                                    ->returnFeesLabel('تتحمل صن رول تكاليف الشحن في حالة وجود عيب في التصنيع')
                                    ->returnFeesURL(url('/blogs/alshot-oalahkam'))
                                    ->returnFeesCurrency('SAR')
                            )
                            ->priceValidUntil('2025-05-31')
                            ->shippingDetails(
                                    Schema::OfferShippingDetails()
                                        ->shippingDestination(
                                            Schema::DefinedRegion()
                                                ->addressCountry('SA')
                                        )
                                        ->shippingRate(
                                            Schema::MonetaryAmount()
                                                ->value(80)
                                                ->currency('SAR')
                                        )
                                        ->deliveryTime(
                                            Schema::ShippingDeliveryTime()
                                                ->handlingTime(
                                                    Schema::QuantitativeValue()
                                                        ->minValue(5)
                                                        ->maxValue(5)
                                                        ->unitCode('DAY')
                                                )
                                                ->transitTime(
                                                    Schema::QuantitativeValue()
                                                        ->minValue(3)
                                                        ->maxValue(3)
                                                        ->unitCode('DAY')
                                                )
                                                ->businessDays(
                                                    Schema::OpeningHoursSpecification()
                                                        ->dayOfWeek([
                                                            'Monday',
                                                            'Tuesday',
                                                            'Wednesday',
                                                            'Thursday',
                                                            'Friday',
                                                            'Saturday'
                                                        ])
                                                        ->opens('08:00:00')
                                                        ->closes('17:00:00')
                                                )
                                                ->cutoffTime('12:00:00')
                                        )
                                        ->shippingMethod('http://purl.org/goodrelations/v1#DeliveryModeStandard')
                                        ->shippingLabel('توصيل مجاني داخل الرياض')
                                )
                            // ->itemOffered(
                            //     Schema::Product()
                            //         ->name($this->productModel->name)
                            //         ->description($this->productModel->description ?? 'مجموعة من الستائر والكنب والمجالس العربية بتصاميم عصرية.')
                            //         ->image(array_map(fn($image) => asset('storage/' . $image), $this->productModel->image ?? []))
                            //         ->sku($this->productModel->sku ?? 'SR-Combo')
                            // )
                            ->price($this->productModel->price ?? '150')
                            ->itemCondition('https://schema.org/NewCondition')
                            ->availability('https://schema.org/InStock')
                            ->seller(
                                Schema::Organization()
                                    ->name('Sun Roll')
                                    ->url('https://sunroll.com.sa')
                            )
                    )
                    ->aggregateRating(
                        Schema::AggregateRating()
                            ->ratingValue('4.8')
                            ->reviewCount(930)
                            ->bestRating('5')
                            ->worstRating('1')
                    )
                    // ->review([
                    //     Schema::Review()
                    //         ->author(Schema::Person()->name('محمد أحمد'))
                    //         ->datePublished('2023-10-01')
                    //         ->reviewBody('الستائر رائعة والجودة ممتازة. أنصح الجميع بشرائها.')
                    //         ->reviewRating(
                    //             Schema::Rating()
                    //                 ->ratingValue('5')
                    //                 ->bestRating('5')
                    //                 ->worstRating('1')
                    //         )
                    // ])

            );
    }

    public function title(): string
    {
        $baseTitle = $this->metaTitle;
        $fullSuffix = ' | الستائر والكنب والمجالس العربية صن رول';

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

    public function desc(): string {
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

    private function findOptimalBreak(string $text, string $breakPoint, int $min, int $max): int|false {
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

    public function handleProductChange($category, $product)
    {
        $this->category = $category;
        $this->product = $product;
        $this->loadProduct();
        // Optional: Update the browser URL without reloading the page
        $this->dispatch('updateUrl', route('curtains', ['category' => $category, 'product' => $product]));
    }


}
