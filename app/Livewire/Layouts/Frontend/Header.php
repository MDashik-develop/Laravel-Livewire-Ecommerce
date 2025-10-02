<?php

namespace App\Livewire\Layouts\Frontend;

use App\Models\Category;
use Livewire\Component;

class Header extends Component
{
    public $categorys ;
    public function render()
    {
        $this->categorys = Category::all();
        return view('livewire.layouts.frontend.header');
    }
}
