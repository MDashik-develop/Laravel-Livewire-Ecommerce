<?php

use App\Livewire\Backend\Brands\Index as BrandsIndex;
use App\Livewire\Backend\Categories\Index as CategoriesIndex;
use App\Livewire\Backend\Permission\Index as PermissionIndex;
use App\Livewire\Backend\Products\Index as ProductsIndex;
use App\Livewire\Backend\Stores\Approval as StoreApproval;
use App\Livewire\Backend\Stores\Index as StoreIndex;
use App\Livewire\Backend\SubCategories\Index as SubCategoriesIndex;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;


Route::prefix('admin')->middleware(['auth', 'verified'])->name('backend.')->group(function () {
   
   Route::get('/permissions', PermissionIndex::class)->name('permissions.index');

   Route::get('/categories', CategoriesIndex::class)->name('categories.index');
   Route::get('/sub-categories', SubCategoriesIndex::class)->name('subcategories.index');

   Route::get('/brands', BrandsIndex::class)->name('brands.index');

   Route::get('/stores', StoreIndex::class)->name('stores.index');
   Route::get('/stores/approval', StoreApproval::class)->name('stores.approval');

   Route::get('/products', ProductsIndex::class)->name('products.index');

});


require __DIR__.'/auth.php';