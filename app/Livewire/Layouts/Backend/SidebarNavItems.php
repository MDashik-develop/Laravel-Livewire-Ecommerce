<?php

namespace App\Livewire\Layouts\Backend;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Store;
use App\Models\SubCategory;
use Livewire\Component;

class SidebarNavItems extends Component
{
    
    public int $totalStore;
    public int $totalStoreAproval;
    public int $totalCategory;
    public int $totalSubCategory;
    public int $totalBrands;

    // public function mount()
    // {
    //     $this->totalStore = Store::count();
    //     $this->totalStoreAproval = Store::where('is_approved', false)->count();
    //     $this->totalCategory = Category::count();
    //     $this->totalSubCategory = SubCategory::count();
    //     $this->totalBrands = Brand::count();
    // }
    public function render()
    {
            $this->totalStore = Store::count();
            $this->totalStoreAproval = Store::where('is_approved', false)->count();
            $this->totalCategory = Category::count();
            $this->totalSubCategory = SubCategory::count();
            $this->totalBrands = Brand::count();
            
        return view('livewire.layouts.backend.sidebar-nav-items');
    }
}
