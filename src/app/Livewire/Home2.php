<?php

namespace App\Livewire;

use App\Models\advertisement;
use App\Models\banner;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Client\Request;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\SchemaOrg\Schema;

class Home extends Component
{

    use WithPagination;

    public $categories;
    public $perPage = 6;

    public function mount()
    {
        $this->categories = ProductCategory::all();
    }

    public function loadMore()
    {
        $this->perPage += 6; // Increase the number of items to show
        $this->emit('productsLoaded'); // Emit event for JavaScript
    }

    /**
     * Render the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        seo()
            ->title($title = config('app.name'))
            ->description($description = 'Lorem 2555555555555555555...')
            ->canonical($url = route('home'))
            ->addSchema(
                Schema::webPage()
                    ->name($title)
                    ->description($description)
                    ->url($url)
                    ->author(Schema::organization()->name($title))
            );

        //     $products = Product::with('product_category')
        //         ->active()
        //         ->latest('created_at')
        //         ->paginate(6);

        // $categories = ProductCategory::active();
        // ->scopeactive();

        $products = Product::with('product_category')
            ->active()
            // ->latest('created_at')
            ->paginate($this->perPage);

            $categories =$this->categories;

            $banners = banner::all();

            $advertisements = advertisement::all();

        return view('livewire.home', compact('products','banners','advertisements','categories'));
    }
}