<?php

namespace App\Livewire;

use App\Models\advertisement;
use App\Models\Offers;
use Livewire\Component;

class FloatingTimer extends Component
{
    public $advertisements;
    public $offer;
    public $formattedTitle;


    public function oddColorText($sentance)
    {
        $words = explode(' ', $sentance);
        $this->formattedTitle = '';

        foreach ($words as $index => $word) {
            if ($index % 2 === 0) { // Odd-indexed words (0, 2, 4, ...)
                $this->formattedTitle .= '<span class="fw-6 txt-orange mb-2">' . $word . '</span> ';
            } else { // Even-indexed words (1, 3, 5, ...)
                $this->formattedTitle .= $word . ' ';
            }
        }
        return $this->formattedTitle;
    }
    /**
     * Initialize component data on mount.
     */
    public function mount()
    {
        $this->advertisements = advertisement::active()->get();
        $this->offer = Offers::active()->get()->last();
    }
    public function render()
    {
        return view('livewire.floating-timer');
    }
}