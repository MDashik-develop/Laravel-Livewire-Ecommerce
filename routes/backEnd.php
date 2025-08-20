<?php

use App\Livewire\Backend\Brands\Index as BrandsIndex;
use App\Livewire\Backend\Categories\Index as CategoriesIndex;
use App\Livewire\Backend\Permission\Index as PermissionIndex;
use App\Livewire\Backend\Stores\Approval as StoreApproval;
use App\Livewire\Backend\Stores\Index as StoreIndex;
use App\Livewire\Backend\SubCategories\Index as SubCategoriesIndex;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;


Route::get('/permissions', PermissionIndex::class)->name('backend.permissions.index');

Route::get('/categories', CategoriesIndex::class)->name('backend.categories.index');
Route::get('/sub-categories', SubCategoriesIndex::class)->name('backend.subcategories.index');

Route::get('/brands', BrandsIndex::class)->name('backend.brands.index');

Route::get('/stores', StoreIndex::class)->name('backend.stores.index');
Route::get('/stores/approval', StoreApproval::class)->name('backend.stores.approval');

require __DIR__.'/auth.php';