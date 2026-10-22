<?php

namespace App\Livewire\Layouts\Frontend;

use App\Models\Product;
use Livewire\Component;

class AsideNewProducts extends Component
{
    public function render()
    {
        $newProducts = Product::with(['media', 'galleries.media', 'reviews'])
            ->where('status', true)
            ->latest('id')
            ->take(4)
            ->get();

        return view('livewire.layouts.frontend.aside-new-products', [
            'newProducts' => $newProducts,
        ]);
    }
}
