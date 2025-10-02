<?php

namespace App\Livewire\Layouts\Frontend;

use App\Models\Category;
use App\Models\Product;
use Livewire\Component;

class AsideCategorys extends Component
{
    public $categorys;
    public $categorysTotalProduct;
    public function render()
    {
        $this->categorys = Category::all();
        $this->categorysTotalProduct = Product::with('category')->get()->groupBy('category_id')->map->count();
        return view('livewire.layouts.frontend.aside-categorys');
    }
}
