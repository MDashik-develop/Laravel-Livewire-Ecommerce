<?php

namespace App\Livewire\Backend\Brands;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use App\Models\Brand;
use App\Models\Media;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Flux\Flux;

class Index extends Component
{
    use WithPagination;

    // Toast
    public array $toast = [];

    // Search
    public $search = '';

    // Form fields
    public ?int $brandId = null;
    public string $name = '';
    public string $slug = '';
    public bool $status = false;
    public ?int $media_id = null;
    public ?string $mediaUrl = null;

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
            'media_id' => 'nullable|exists:media,id',
        ];
    }

    #[On('brand-media-selected')]
    public function handleMediaSelected($media): void
    {
        if (is_array($media)) {
            $this->media_id = $media['id'] ?? null;
            $this->mediaUrl = $media['urls']['small'] ?? $media['url'] ?? null;
        } elseif (is_numeric($media)) {
            $this->media_id = (int) $media;
            $m = Media::find($media);
            $this->mediaUrl = $m?->urls['small'] ?? $m?->url;
        }
    }

    public function removeMedia(): void
    {
        $this->media_id = null;
        $this->mediaUrl = null;
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
        $this->reset(['brandId', 'name', 'slug', 'status', 'media_id', 'mediaUrl']);
        $this->status = false;
    }

    public function edit(Brand $brand)
    {
        $this->brandId = $brand->id;
        $this->name = $brand->name;
        $this->slug = $brand->slug;
        $this->status = $brand->status;
        $this->media_id = $brand->media_id;
        $this->mediaUrl = $brand->media?->urls['small'] ?? $brand->media?->url;

        Flux::modal('brand-modal')->show();
    }

    public function save()
    {
        $validatedData = $this->validate();

        if ($this->brandId) {
            $brand = Brand::findOrFail($this->brandId);
            $brand->update($validatedData);

            $this->dispatch('show-toast', [
                'title' => 'Success 🎉',
                'message' => 'Brand updated successfully!',
                'type' => 'success'
            ]);
        } else {
            Brand::create($validatedData);

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
        $brands = Brand::with('media')
            ->where('name', 'like', '%' . $this->search . '%')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.backend.brands.index', [
            'brands' => $brands
        ]);
    }
}
