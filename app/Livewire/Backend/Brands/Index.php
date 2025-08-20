<?php

namespace App\Livewire\Backend\Brands;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Brand;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Flux\Flux;
// use Livewire\Attributes\Layout;

// #[Layout('components.layouts.frontend')]

class Index extends Component
{
    use WithPagination, WithFileUploads;

    // Toast
    public array $toast = [];

    // Search
    public $search = '';

    // Form fields
    public ?int $brandId = null;
    public string $name = '';
    public string $slug = '';
    public bool $status = false;
    public $image;
    public ?string $image_path = null;

    protected function rules()
    {
        return [
            'name' => 'required|string|min:3|max:255',
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('brands')->ignore($this->brandId),
            ],
            'status' => 'required|boolean',
            'image' => $this->brandId ? 'nullable|image|max:3072' : 'required|image|max:3072',
        ];
    }

    public function updatedName($value)
    {
        $this->slug = Str::slug($value);
    }

    public function updatedPaginators()
    {
        $this->search = '';
    }

    public function resetForm()
    {
        $this->reset(['brandId', 'name', 'slug', 'status', 'image', 'image_path']);
        $this->status = false;
    }

    public function edit(Brand $brand)
    {
        $this->brandId = $brand->id;
        $this->name = $brand->name;
        $this->slug = $brand->slug;
        $this->status = $brand->status;
        $this->image_path = $brand->image_path;
        $this->image = null;

        Flux::modal('brand-modal')->show();
    }

    public function save()
    {
        $validatedData = $this->validate();

        if ($this->brandId) {
            // Update existing brand
            $brand = Brand::findOrFail($this->brandId);

            if ($this->image) {
                if ($brand->image_path) {
                    Storage::disk('public')->delete($brand->image_path);
                }
                $extension = $this->image->getClientOriginalExtension();
                $randomString = substr(md5(uniqid()), 0, 3);
                $time = time();
                $customName = "brand-{$brand->id}-{$time}-{$randomString}.{$extension}";
                $validatedData['image_path'] = $this->image->storeAs('brands', $customName, 'public');
            } else {
                $validatedData['image_path'] = $brand->image_path;
            }

            $brand->update($validatedData);

            $this->dispatch('show-toast', [
                'title' => 'Success 🎉',
                'message' => 'Brand updated successfully!',
                'type' => 'success'
            ]);

        } else {
            // Create new brand WITHOUT image first
            $brand = Brand::create([
                'name' => $validatedData['name'],
                'slug' => $validatedData['slug'],
                'status' => $validatedData['status'],
                'image_path' => null, // temporarily null
            ]);

            // Now handle image if uploaded
            if ($this->image) {
                $extension = $this->image->getClientOriginalExtension();
                $randomString = substr(md5(uniqid()), 0, 3);
                $time = time();
                $customName = "brand-{$brand->id}-{$time}-{$randomString}.{$extension}";
                $imagePath = $this->image->storeAs('brands', $customName, 'public');

                $brand->update(['image_path' => $imagePath]);
            }

            $this->dispatch('show-toast', [
                'title' => 'Success 🎉',
                'message' => 'Brand created successfully!',
                'type' => 'success'
            ]);
        }

        Flux::modal('brand-modal')->close();
        $this->resetForm();
        $this->resetPage();
    }


    public function confirmDelete($id)
    {
        $this->brandId = $id;
    }

    public function delete()
    {
        $brand = Brand::findOrFail($this->brandId);

        if (!empty($brand->image_path) && Storage::disk('public')->exists($brand->image_path)) {
            Storage::disk('public')->delete($brand->image_path);
        }

        $brand->delete();

        Flux::modal('delete-modal')->close();
        $this->resetForm();

        $this->dispatch('show-toast', [
            'title' => 'Success 🎉',
            'message' => 'Brand deleted successfully!',
            'type' => 'success'
        ]);
    }

    public function render()
    {
        $brands = Brand::where('name', 'like', '%' . $this->search . '%')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.backend.brands.index', [
            'brands' => $brands
        ]);
    }

    
}
