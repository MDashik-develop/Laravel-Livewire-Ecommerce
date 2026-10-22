<?php

namespace App\Livewire\Layouts\Frontend;

use App\Models\Banner;
use Livewire\Component;

class AsideBanner extends Component
{
    public function render()
    {
        $banner = Banner::with(['media', 'category', 'product'])
            ->where('status', true)
            ->where(function ($q) {
                $q->where('section', 'sidebar')
                  ->orWhere('section', 'aside');
            })
            ->orderBy('position', 'asc')
            ->latest('id')
            ->first();

        return view('livewire.layouts.frontend.aside-banner', [
            'banner' => $banner,
        ]);
    }
}
