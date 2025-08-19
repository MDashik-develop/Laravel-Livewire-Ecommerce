<?php

namespace App\Livewire\Utilities;

use Flux\Flux;
use Livewire\Component;

class ToastModal extends Component

{
    public array $toast = [];

    protected $listeners = ['show-toast' => 'showToast']; // এখানে listener

    public function showToast($data)
    {
        $this->toast = $data;
        Flux::modal('toast-modal')->show(); // modal ওপেন হবে
    }
    public function render()
    {
        return view('livewire.utilities.toast-modal');
    }
}
