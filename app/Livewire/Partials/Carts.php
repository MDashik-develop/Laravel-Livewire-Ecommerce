<?php

namespace App\Livewire\Partials;

use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Carts extends Component
{
    public $cartCount = 0;

    protected $listeners = ['cartUpdated' => 'updateCartCount'];

    public function mount()
    {
        $this->updateCartCount();
    }

    public function updateCartCount()
    {
        if (Auth::check()) {
            $this->cartCount = Auth::user()->carts()->sum('quantity');
        } else {
            $cart = Session::get('cart', []);
            $this->cartCount = array_sum(array_column($cart, 'quantity'));
        }

    }

    public function render()
    {
        return view('livewire.partials.carts');
    }
}
