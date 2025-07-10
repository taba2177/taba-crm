<?php

namespace Taba\Crm\Livewire;

use Taba\Crm\Models\advertisement;
use Taba\Crm\Models\banner;
use Taba\Crm\Models\Product;
use Taba\Crm\Models\ProductCategory;
use Illuminate\Http\Client\Request;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\SchemaOrg\Schema;

class Home2 extends Component
{

    use WithPagination;

    public $categories;
    public $perPage = 6;

    public function mount()
    {
        $this->categories = \Taba\Crm\Models\ProductCategory::all();
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

        //     $products = \Taba\Crm\Models\Product::with('product_category')
        //         ->active()
        //         ->latest('created_at')
        //         ->paginate(6);

        // $categories = \Taba\Crm\Models\ProductCategory::active();
        // ->scopeactive();

        $products = \Taba\Crm\Models\Product::with('product_category')
            ->active()
            // ->latest('created_at')
            ->paginate($this->perPage);

            $categories =$this->categories;

            $banners = \Taba\Crm\Models\banner::all();

            $advertisements = \Taba\Crm\Models\advertisement::all();

        return view('livewire.home', compact('products','banners','advertisements','categories'));
    }
}