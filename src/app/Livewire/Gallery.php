<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\ProductCategory;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\SchemaOrg\Schema;


class Gallery extends Component
{
    use WithPagination;

    public $selectedCategory = '*'; // Default filter: show all
    public $perPage = 4; // Number of products to show initially
    public $hasMoreProducts = false; // Track if there are more products to load
    public $categories; // Declare the categories property

    protected function checkForMoreProducts()
    {
        $totalProducts = Product::when($this->selectedCategory !== '*', function ($query) {
            $query->where('product_category_id', $this->selectedCategory);
        })->count();

        $this->hasMoreProducts = $this->perPage < $totalProducts;
    }

    /**
     * Filter products by category.
     */
    public function filterByCategory($categoryId)
    {
        $this->selectedCategory = $categoryId;
        // $this->resetPage();
    }


    /**
     * Load more products.
     */
    public function loadMore()
    {
        $this->perPage += 4; // Increase the number of products to show
        $this->checkForMoreProducts(); // Check if there are more products to load
    }

    public function mount()
    {
        // Fetch categories with their products
        $this->categories = ProductCategory::with(['products' => function ($query) {
            $query->where('is_active', true); // Only fetch active products
        }])->active()->get();
    }

    /**
     * Render the component.
     */
    public function render()
    {
        // Get the selected category
        $query = Product::with('product_category');

        if ($this->selectedCategory !== '*') {
            // Find the parent category
            $parentCategory = ProductCategory::find($this->selectedCategory);

            if ($parentCategory) {
                // Get all child category IDs
                $childCategoryIds = \Taba\Crm\Models\ProductCategory::where('parent_id', $parentCategory->id)->pluck('id')->toArray();

                // Include the parent and its child categories in the product query
                $query->whereIn('product_category_id', array_merge([$parentCategory->id], $childCategoryIds));
            }
        }
        if (!request()->routeIs('home')) {
            $this->setSeoMetadata();
            $products = $query//->latest('created_at')
            ->orderBy('priority', 'desc')
            ->where('is_active', true)
            ->get();

            }
         else{
            // Get the products with pagination
            $products = $query//->latest('created_at')
            ->orderBy('priority', 'desc')
                ->where('is_active', true)
            ->paginate($this->perPage);
        }
        return view('livewire.gallery', [
            'products' => $products,
            'categories' => $this->categories,
        ]);
    }
        protected function setSeoMetadata()

    {
        seo()
        ->title('صن رول للستائر والديكور - ستائر زيبرا، معدنية، رومان، خشبية')
        ->description('اكتشف تشكيلتنا الواسعة من الستائر بما في ذلك ستائر زيبرا، معدنية، رول، رومان، وخشبية، لتجميل منزلك بأفضل الحلول الديكورية')
            ->canonical(route('gallery'))
            ->addSchema(
            Schema::webPage()
                ->name('معرض احدث ستائر وكنب  من شركة صن رول للستائر')
                ->description('اكتشف تشكيلتنا الواسعة من الستائر بما في ذلك ستائر زيبرا، معدنية، رول، رومان، وخشبية، لتجميل منزلك بأفضل الحلول الديكورية')
                ->url(route('gallery'))
                ->author(Schema::organization()->name(config('app.name')))
            );
        }

}
