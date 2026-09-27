<?php

namespace App\Livewire\Backend\Categories;

use Flux\Flux;
use Livewire\Component;
use App\Models\Category;
use App\Models\Media;
use Illuminate\Support\Str;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use Illuminate\Validation\Rule;

class Index extends Component
{
    use WithPagination;

    //Toast flux message
    public array $toast = [];
    // Search and filtering properties
    public $search = '';

    // Category properties for form binding
    public ?int $categoryId = null;
    public string $name = '';
    public string $slug = '';
    public bool $status = true;
    public ?int $media_id = null;
    public ?string $mediaUrl = null;
    public ?int $banner_media_id = null;
    public ?string $bannerMediaUrl = null;

    // Rules for validation
    protected function rules()
    {
        return [
            'name' => 'required|string|min:3|max:255',
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories')->ignore($this->categoryId),
            ],
            'status' => 'required|boolean',
            'media_id' => 'nullable|exists:media,id',
            'banner_media_id' => 'nullable|exists:media,id',
        ];
    }

    #[On('category-media-selected')]
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

    #[On('category-banner-media-selected')]
    public function handleBannerMediaSelected($media): void
    {
        if (is_array($media)) {
            $this->banner_media_id = $media['id'] ?? null;
            $this->bannerMediaUrl = $media['urls']['large'] ?? $media['url'] ?? null;
        } elseif (is_numeric($media)) {
            $this->banner_media_id = (int) $media;
            $m = Media::find($media);
            $this->bannerMediaUrl = $m?->urls['large'] ?? $m?->url;
        }
    }

    public function removeBannerMedia(): void
    {
        $this->banner_media_id = null;
        $this->bannerMediaUrl = null;
    }
    
    // Automatically generate slug when name is updated
    public function updatedName($value)
    {
        $this->slug = Str::slug($value);
    }
    
    // Reset search when page changes
    public function updatedPaginators()
    {
        $this->search = '';
    }

    // Method to set up the modal for editing a category
    public function edit(Category $category)
    {
        $this->categoryId = $category->id;
        $this->name = $category->name;
        $this->slug = $category->slug;
        $this->status = (bool) $category->status;
        $this->media_id = $category->media_id;
        $this->mediaUrl = $category->media?->urls['small'] ?? $category->media?->url;
        $this->banner_media_id = $category->banner_media_id;
        $this->bannerMediaUrl = $category->bannerMedia?->urls['large'] ?? $category->bannerMedia?->url;

        Flux::modal('category-modal')->show();
    }

    // Method to save or update a category
    public function save()
    {
        $validatedData = $this->validate();

        if ($this->categoryId) {
            $category = Category::findOrFail($this->categoryId);
            $category->update($validatedData);

            $this->dispatch('show-toast', [
                'title' => 'Success 🎉',
                'message' => 'Category updated successfully!',
                'type' => 'success'
            ]);
        } else {
            Category::create($validatedData);

            $this->dispatch('show-toast', [
                'title' => 'Success 🎉',
                'message' => 'Category created successfully!',
                'type' => 'success'
            ]);
        }

        Flux::modal('category-modal')->close();
        $this->resetForm();
        $this->resetPage();
    }
    
    public function confirmDelete($id)
    {
        $this->categoryId = $id;
    }

    public function delete()
    {
        $category = Category::findOrFail($this->categoryId);
        $category->delete();
        
        Flux::modal('delete-modal')->close();
        $this->resetForm();
        
        $this->dispatch('show-toast', [
            'title' => 'Success 🎉',
            'message' => 'Category deleted successfully!',
            'type' => 'success'
        ]);
    }

    // Reset form fields
    public function resetForm()
    {
        $this->reset(['categoryId', 'name', 'slug', 'status', 'media_id', 'mediaUrl', 'banner_media_id', 'bannerMediaUrl']);
        $this->status = true; // Default status
    }

    // The main render method
    public function render()
    {
        $categories = Category::with(['media', 'bannerMedia'])
            ->where('name', 'like', '%' . $this->search . '%')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.backend.categories.index', [
            'categories' => $categories,
        ]); 
    }
}