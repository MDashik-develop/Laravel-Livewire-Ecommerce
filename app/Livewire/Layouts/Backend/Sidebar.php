<?php

namespace App\Livewire\Layouts\Backend;

use App\Models\Store;
use Livewire\Component;

class Sidebar extends Component
{
    public int $totalStore;

    public function mount()
    {
        $this->totalStore = Store::count();
    }

    public function render()
    {
        return view('livewire.layouts.backend.sidebar');
    }
}
