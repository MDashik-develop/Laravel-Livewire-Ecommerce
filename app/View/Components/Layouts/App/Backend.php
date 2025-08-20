<?php

namespace App\View\Components\Layouts\App;

use App\Models\Store;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Backend extends Component
{
    public string $title;


    /**
     * Create a new component instance.
     */
    public function __construct(string $title = 'Dashboard')
    {

        // title dynamic করতে পারবো
        $this->title = $title;
        
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.layouts.app.backend');
    }
}
