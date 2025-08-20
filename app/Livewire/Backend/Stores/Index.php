<?php

namespace App\Livewire\Backend\Stores;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Models\Store;
use App\Models\User; // assuming you want to select user

class Index extends Component
{
    use WithPagination, WithFileUploads;

    public array $toast = [];
    public $search = '';

    public ?int $storeId = null;
    public ?int $user_id = null;
    public string $name = '';
    public string $slug = '';
    public ?string $description = null;
    public ?string $logo_path = null;
    public $logo;
    public string $phone = '';
    public string $address = '';
    public bool $status = true;
    public bool $is_approved = false;

    protected function rules()
    {
        return [
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|min:3|max:255',
            'slug' => [
                'required',
                'string',
                'max:255',
                $this->storeId ? "unique:stores,slug,{$this->storeId}" : 'unique:stores,slug'
            ],
            'description' => 'nullable|string',
            'logo' => $this->storeId ? 'nullable|image|max:3072' : 'required|image|max:3072',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'status' => 'required|boolean',
            'is_approved' => 'required|boolean',
        ];
    }

    public function updatedName($value)
    {
        $this->slug = Str::slug($value);
    }

    public function edit(Store $store)
    {
        $this->storeId = $store->id;
        $this->user_id = $store->user_id;
        $this->name = $store->name;
        $this->slug = $store->slug;
        $this->description = $store->description;
        $this->phone = $store->phone;
        $this->address = $store->address;
        $this->status = $store->status;
        $this->is_approved = $store->is_approved;
        $this->logo_path = $store->logo;
        $this->logo = null;

        \Flux\Flux::modal('store-modal')->show();
    }

    public function save()
    {
        $validated = $this->validate();

        if ($this->storeId) {
            $store = Store::findOrFail($this->storeId);

            if ($this->logo) {
                if ($store->logo) Storage::disk('public')->delete($store->logo);

                $name = "store-{$store->id}-".time()."-".substr(md5(uniqid()),0,3).".".$this->logo->getClientOriginalExtension();
                $validated['logo'] = $this->logo->storeAs('stores', $name, 'public');
            } else {
                $validated['logo'] = $store->logo;
            }

            $store->update($validated);

            $this->dispatch('show-toast', [
                'title' => 'Success 🎉',
                'message' => 'Store updated successfully!',
                'type' => 'success'
            ]);

        } else {
            $store = Store::create(array_merge($validated, ['logo' => 'temp.png']));

            if ($this->logo) {
                $name = "store-{$store->id}-".time()."-".substr(md5(uniqid()),0,3).".".$this->logo->getClientOriginalExtension();
                $path = $this->logo->storeAs('stores', $name, 'public');
                $store->update(['logo' => $path]);
            }

            $this->storeId = $store->id;
            $this->dispatch('show-toast', [
                'title' => 'Success 🎉',
                'message' => 'Store created successfully!',
                'type' => 'success'
            ]);
        }

        \Flux\Flux::modal('store-modal')->close();
        $this->resetForm();
        $this->resetPage();
    }

    public function confirmDelete($id)
    {
        $this->storeId = $id;
    }

    public function delete()
    {
        $store = Store::findOrFail($this->storeId);
        if ($store->logo && Storage::disk('public')->exists($store->logo)) {
            Storage::disk('public')->delete($store->logo);
        }
        $store->delete();

        \Flux\Flux::modal('delete-modal')->close();
        $this->resetForm();

        $this->dispatch('show-toast', [
            'title' => 'Success 🎉',
            'message' => 'Store deleted successfully!',
            'type' => 'success'
        ]);
    }

    public function resetForm()
    {
        $this->reset(['storeId','user_id','name','slug','description','logo','logo_path','phone','address','status','is_approved']);
        $this->status = true;
        $this->is_approved = false;
    }

    public function render()
    {
        $stores = Store::with('user')
            ->where('name', 'like', '%'.$this->search.'%')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $users = User::all();

        return view('livewire.backend.stores.index', compact('stores','users'));
    }
}
