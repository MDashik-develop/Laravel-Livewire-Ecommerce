<?php

namespace App\Livewire\Backend\SubCategories;

use Livewire\Component;
use Flux\Flux;
use App\Models\SubCategory;
use App\Models\Category;
use App\Models\Media;
use Illuminate\Support\Str;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use Illuminate\Validation\Rule;

class Index extends Component
{
    use WithPagination;

    // Toast message
    public array $toast = [];

    // Search and filtering
    public $search = '';

    // SubCategory properties
    public ?int $subCategoryId = null;
    public ?int $category_id = null;
    public string $name = '';
    public string $slug = '';
    public bool $status = true;
    public ?int $media_id = null;
    public ?string $mediaUrl = null;
    public ?int $banner_media_id = null;
    public ?string $bannerMediaUrl = null;

    protected function rules()
    {
        return [
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|min:3|max:255',
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('sub_categories')->ignore($this->subCategoryId),
            ],
            'status' => 'required|boolean',
            'media_id' => 'nullable|exists:media,id',
            'banner_media_id' => 'nullable|exists:media,id',
        ];
    }

    #[On('subcategory-media-selected')]
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

    public function edit(SubCategory $subCategory)
    {
        $this->subCategoryId = $subCategory->id;
        $this->category_id = $subCategory->category_id;
        $this->name = $subCategory->name;
        $this->slug = $subCategory->slug;
        $this->status = (bool) $subCategory->status;
        $this->media_id = $subCategory->media_id;
        $this->mediaUrl = $subCategory->media?->urls['small'] ?? $subCategory->media?->url;
        $this->banner_media_id = $subCategory->banner_media_id;
        $this->bannerMediaUrl = $subCategory->bannerMedia?->urls['large'] ?? $subCategory->bannerMedia?->url;

        Flux::modal('sub-category-modal')->show();
    }

    public function save()
    {
        $validatedData = $this->validate();

        if ($this->subCategoryId) {
            $subCategory = SubCategory::findOrFail($this->subCategoryId);
            $subCategory->update($validatedData);

            $this->dispatch('show-toast', [
                'title' => 'Success 🎉',
                'message' => 'SubCategory updated successfully!',
                'type' => 'success'
            ]);

        } else {
            SubCategory::create($validatedData);

            $this->dispatch('show-toast', [
                'title' => 'Success 🎉',
                'message' => 'SubCategory created successfully!',
                'type' => 'success'
            ]);
        }

        $this->dispatch('close-modal', name: 'sub-category-modal');
        Flux::modal('sub-category-modal')->close();
        $this->resetForm();
        $this->resetPage();
    }

    public function confirmDelete($id)
    {
        $this->subCategoryId = $id;
    }

    public function delete()
    {
        $subCategory = SubCategory::findOrFail($this->subCategoryId);
        $subCategory->delete();

        Flux::modal('delete-modal')->close();
        $this->resetForm();

        $this->dispatch('show-toast', [
            'title' => 'Success 🎉',
            'message' => 'SubCategory deleted successfully!',
            'type' => 'success'
        ]);
    }

    public function resetForm()
    {
        $this->reset(['subCategoryId', 'category_id', 'name', 'slug', 'status', 'media_id', 'mediaUrl', 'banner_media_id', 'bannerMediaUrl']);
        $this->status = true;
    }

    public function render()
    {
        $subCategories = SubCategory::with(['category', 'media', 'bannerMedia'])
            ->where('name', 'like', '%' . $this->search . '%')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $categories = Category::all();

        return view('livewire.backend.sub-categories.index', [
            'subCategories' => $subCategories,
            'categories' => $categories
        ]);
    }
}
