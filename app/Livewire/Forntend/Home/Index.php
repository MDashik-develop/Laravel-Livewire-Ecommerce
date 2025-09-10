<?php

namespace App\Livewire\Forntend\Home;

use Livewire\Component;

class Index extends Component
{
    public $products;
    public function mount()
    {
        $this->products = \App\Models\Product::all();
    }
    public function render()
    {
        return view('livewire.forntend.home.index');
    }
}
