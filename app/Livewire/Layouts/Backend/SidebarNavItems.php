<?php

namespace App\Livewire\Layouts\Backend;

use App\Models\Banner;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
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
    public int $totalProducts;
    public int $totalBanners;

    public function render()
    {
            $this->totalStore = Store::count();
            $this->totalStoreAproval = Store::where('is_approved', false)->count();
            $this->totalCategory = Category::count();
            $this->totalSubCategory = SubCategory::count();
            $this->totalBrands = Brand::count();
            $this->totalProducts = Product::count();
            $this->totalBanners = Banner::count();
            
        return view('livewire.layouts.backend.sidebar-nav-items');
    }
}
