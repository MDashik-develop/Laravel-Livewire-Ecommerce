<?php

namespace App\View\Components\Layouts\App;

use App\Models\Store;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Frontend extends Component
{

    public function __construct()
    {
    }
    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.layouts.app.frontend');
    }
}
