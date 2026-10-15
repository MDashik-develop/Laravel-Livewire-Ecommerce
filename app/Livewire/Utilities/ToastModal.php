<?php

namespace App\Livewire\Utilities;

use Flux\Flux;
use Livewire\Attributes\On;
use Livewire\Component;

class ToastModal extends Component
{
    public array $toast = [];

    protected $listeners = ['show-toast' => 'showToast'];

    #[On('show-toast')]
    public function showToast($data = null, $title = null, $message = null, $type = 'success'): void
    {
        if (is_array($data)) {
            $this->toast = $data;
        } else {
            $this->toast = [
                'title'   => $title ?? (is_string($data) ? $data : 'Notice'),
                'message' => $message ?? '',
                'type'    => $type ?? 'success',
            ];
        }

        Flux::modal('toast-modal')->show();
    }

    public function render()
    {
        return view('livewire.utilities.toast-modal');
    }
}
