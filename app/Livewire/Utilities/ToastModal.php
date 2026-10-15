<?php

namespace App\Livewire\Utilities;

use Livewire\Attributes\On;
use Livewire\Component;

class ToastModal extends Component
{
    public bool $isOpen = false;
    public array $toast = [
        'title'   => '',
        'message' => '',
        'type'    => 'success',
    ];

    protected $listeners = ['show-toast' => 'showToast'];

    #[On('show-toast')]
    public function showToast($data = null, $title = null, $message = null, $type = 'success'): void
    {
        if (is_array($data)) {
            $this->toast = [
                'title'   => $data['title'] ?? 'Notice',
                'message' => $data['message'] ?? '',
                'type'    => $data['type'] ?? 'success',
            ];
        } else {
            $this->toast = [
                'title'   => $title ?? (is_string($data) ? $data : 'Notice'),
                'message' => $message ?? '',
                'type'    => $type ?? 'success',
            ];
        }

        $this->isOpen = true;
    }

    public function closeToast(): void
    {
        $this->isOpen = false;
    }

    public function render()
    {
        return view('livewire.utilities.toast-modal');
    }
}
