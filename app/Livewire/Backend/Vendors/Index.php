<?php

namespace App\Livewire\Backend\Vendors;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Store;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class Index extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $name, $slug, $description, $logo, $phone, $address, $is_approved = false, $status = true;
    public $storeId;
    public $vendorId ;
    public $logo_path;

    protected $rules = [
        'name' => 'required|string|max:255',
        'slug' => 'required|string|max:255|unique:stores,slug',
        'description' => 'nullable|string',
        'logo' => 'nullable|image|max:1024',
        'phone' => 'required|string|max:20',
        'address' => 'required|string',
        'is_approved' => 'boolean',
        'status' => 'boolean',
    ];

    public function updatedName($value)
    {
        $this->slug = Str::slug($value);
    }

    public function resetForm()
    {
        $this->reset(['name','slug','description','logo','phone','address','is_approved','status','storeId','logo_path']);
    }

    public function save()
    {
        $validatedData = $this->validate();

        if ($this->storeId) {
            // Update existing store
            $store = Store::findOrFail($this->storeId);

            if ($this->logo) {
                if ($store->logo) {
                    Storage::disk('public')->delete($store->logo);
                }
                $ext = $this->logo->getClientOriginalExtension();
                $filename = "store-{$store->id}-".time()."-".substr(md5(uniqid()),0,3).".".$ext;
                $validatedData['logo'] = $this->logo->storeAs('stores', $filename, 'public');
            } else {
                $validatedData['logo'] = $store->logo;
            }

            $store->update($validatedData);

            $this->dispatch('show-toast', [
                'title'=>'Success 🎉',
                'message'=>'Store updated successfully!',
                'type'=>'success'
            ]);

        } else {
            // Create new store
            $userId = auth()->id() ?? 1; // 1 = default admin
            $store = Store::create(array_merge(
                $validatedData,
                [
                    'logo' => 'temp.png',
                    'user_id' => $userId // 👈 Assign logged-in user
                ]
            ));

            if ($this->logo) {
                $ext = $this->logo->getClientOriginalExtension();
                $filename = "store-{$store->id}-".time()."-".substr(md5(uniqid()),0,3).".".$ext;
                $logoPath = $this->logo->storeAs('stores', $filename, 'public');
                $store->update(['logo' => $logoPath]);
            }

            $this->dispatch('show-toast', [
                'title'=>'Success 🎉',
                'message'=>'Store created successfully!',
                'type'=>'success'
            ]);
        }

        $this->resetForm();
        $this->resetPage();
        $this->dispatch('close-modal', name:'vendor-modal');
    }

    public function edit(Store $store)
    {
        $this->storeId = $store->id;
        $this->name = $store->name;
        $this->slug = $store->slug;
        $this->description = $store->description;
        $this->phone = $store->phone;
        $this->address = $store->address;
        $this->is_approved = $store->is_approved;
        $this->status = $store->status;
        $this->logo_path = $store->logo;

        $this->dispatch('open-modal', name:'vendor-modal');
    }

    public function delete(Store $store)
    {
        if ($store->logo) {
            Storage::disk('public')->delete($store->logo);
        }
        $store->delete();

        $this->dispatch('show-toast', [
            'title'=>'Deleted ❌',
            'message'=>'Store deleted successfully!',
            'type'=>'error'
        ]);
    }

    public function render()
    {
        $stores = Store::where('name','like','%'.$this->search.'%')
                        ->orWhere('slug','like','%'.$this->search.'%')
                        ->paginate(10);

        return view('livewire.backend.vendors.index', [
            'vendors' => $stores, // 👈 Blade এ $vendors variable
        ]);
    }
}
