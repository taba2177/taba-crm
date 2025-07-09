<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Studio;
use Spatie\SchemaOrg\Schema;


class GalleryImages extends Component
{
    use WithPagination;

    public $show = 'notall';
    protected $queryString = ['show'];
    public $randomCategory;



    public function mount($show = 'notall')
    {
        $this->show = $show;
        $this->randomCategory = $this->randomCategory();
    }
    // In GalleryImages component
    protected function randomCategory()
    {
        return collect(['design', 'photography', 'illustration', 'identity', 'business'])->random();
    }
    public function render()
    {
        if (!request()->routeIs('home')) {

        $this->setSeoMetadata();}
        $query = Studio::active()->with('product')
            ->when($this->show !== 'all', fn($q) => $q->where('id', '!=', 10)->limit(2)->inRandomOrder())
            ->when($this->show === 'all', fn($q) => $q->latest()->inRandomOrder());


        return view('livewire.gallery-images', [
            'galleries' => $this->show !== 'all'
                ? $query->get()
                : $query->get(),
            'randomCategory' => $this->randomCategory,
        ]);
    }

    protected function setSeoMetadata()

    {
        seo()
            ->title('ستائر رول، ستائر امريكي، ستائر يدوية | صن رول للستائر والكنب')
            ->description('استعرض تشكيلة واسعة من صور الستائر المتنوعة - ستائر رول، ستائر امريكي، ستائر يدوية. تصاميم عصرية وخامات عالية الجودة لجميع الغرف والمكاتب والمستشفيات')
            ->canonical(route('gallery-images','all'))
            ->addSchema(
            Schema::webPage()
                ->name(config('app.name'))
                ->description('معرض صور متكامل لجميع انواع الستائر - ستائر رول، ستائر امريكي، ستائر يدوية. تصاميم عصرية وخامات عالية الجودة مناسبة للمنازل والمكاتب والمستشفيات')
                ->url(route('gallery-images', ['show' => 'all']))
                ->author(Schema::organization()->name(config('app.name')))
            );
    }
}
