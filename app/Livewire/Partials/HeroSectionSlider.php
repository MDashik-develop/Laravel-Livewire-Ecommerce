<?php

namespace App\Livewire\Partials;

use App\Models\Banner;
use Livewire\Component;

class HeroSectionSlider extends Component
{
    public function render()
    {
        $banners = Banner::with(['media', 'videoMedia', 'category', 'product'])
            ->where('status', true)
            ->where(function ($q) {
                $q->where('section', 'slider')
                  ->orWhereNull('section')
                  ->orWhere('section', '');
            })
            ->orderBy('position', 'asc')
            ->orderBy('id', 'desc')
            ->get();

        return view('livewire.partials.hero-section-slider', [
            'banners' => $banners,
        ]);
    }
}

