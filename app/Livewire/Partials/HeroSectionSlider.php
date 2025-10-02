<?php

namespace App\Livewire\Partials;

use Livewire\Component;

class HeroSectionSlider extends Component
{

    public function placeholder()
    {
        return <<<'HTML'
        <div class="swiper-slide relative bg-gray-100 rounded-xl h-full flex items-center p-12 animate-pulse">
            <div class="absolute inset-0 bg-gray-200 opacity-80 rounded-lg"></div>
            <div class="relative z-10 w-full space-y-4">
                <!-- Title -->
                <div class="h-10 bg-gray-300 rounded w-2/3"></div>
                <!-- Subtitle -->
                <div class="h-6 bg-gray-200 rounded w-1/3"></div>

                <!-- Input + Button -->
                <div class="flex mt-6">
                    <div class="h-12 bg-gray-300 rounded-l-full flex-grow"></div>
                    <div class="h-12 bg-gray-400 rounded-r-full w-32"></div>
                </div>
            </div>
        </div>
        HTML;
    }
    public function render()
    {
        return view('livewire.partials.hero-section-slider');
    }
}
