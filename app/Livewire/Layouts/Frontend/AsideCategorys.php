<?php

namespace App\Livewire\Layouts\Frontend;

use App\Models\Category;
use Livewire\Component;

class AsideCategorys extends Component
{
    public function render()
    {
        $categories = Category::withCount('products')->get();

        return view('livewire.layouts.frontend.aside-categorys', [
            'categorys' => $categories,
        ]);
    }
}
