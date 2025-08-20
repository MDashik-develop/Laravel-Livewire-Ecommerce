<?php

namespace App\Http\Livewire\Layouts;

use Livewire\Component;
use App\Models\Store;

class Backend extends Component
{
    public $title;
    public $totalStore;

    public function mount($title = 'Dashboard')
    {
        $this->title = $title;
        $this->totalStore = Store::count();
    }

    public function refreshData()
    {
        // Update total stores dynamically
        $this->totalStore = Store::count();
    }

    public function render()
    {
        return view('livewire.layouts.backend');
    }
}
