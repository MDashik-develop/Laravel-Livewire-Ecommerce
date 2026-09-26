<?php

namespace App\Livewire\Layouts\Frontend;

use App\Models\Category;
use Livewire\Component;

class Header extends Component
{
    public function render()
    {
        return view('livewire.layouts.frontend.header', [
            'categorys' => Category::all(),
        ]);
    }
}
