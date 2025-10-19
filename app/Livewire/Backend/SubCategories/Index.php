<?php

namespace App\Livewire\Backend\SubCategories;

use Livewire\Component;
use Flux\Flux;
use App\Models\SubCategory;
use App\Models\Category;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class Index extends Component
{
    use WithPagination, WithFileUploads;

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
    public $image;
    public ?string $image_path = null;

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
            'image' => $this->subCategoryId ? 'nullable|image|max:3072' : 'required|image|max:3072',
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

    public function edit(SubCategory $subCategory)
    {
        $this->subCategoryId = $subCategory->id;
        $this->category_id = $subCategory->category_id;
        $this->name = $subCategory->name;
        $this->slug = $subCategory->slug;
        $this->status = $subCategory->status;
        $this->image_path = $subCategory->image_path;
        $this->image = null;

        Flux::modal('sub-category-modal')->show();
    }

    public function save()
    {
        $validatedData = $this->validate();

        // Create ImageManager instance
        $manager = new ImageManager(new Driver());

        if ($this->subCategoryId) {
            // Update existing subcategory
            $subCategory = SubCategory::findOrFail($this->subCategoryId);

            // Handle image upload
            if ($this->image) {
                if ($subCategory->image_path) {
                    Storage::disk('public')->delete($subCategory->image_path);
                }

                // $extension = $this->image->getClientOriginalExtension();
                $extension = "webp";
                $randomString = substr(md5(uniqid()), 0, 3);
                $time = time();
                $customName = "subctgry-{$subCategory->id}-{$time}-{$randomString}.{$extension}";

                $image = $manager->read($this->image->getRealPath()); // ✅ একই manager ব্যবহার
                $image->encodeByExtension('webp', 85);

                // $validatedData['image_path'] = $this->image->storeAs('sub_categories', $customName, 'public');
                $path = 'sub_categories/' . $customName;
                Storage::disk('public')->put($path, (string) $image->encode());
                $validatedData['image_path'] = $path;
            } else {
                $validatedData['image_path'] = $subCategory->image_path;
            }

            $subCategory->update($validatedData);

            $this->dispatch('show-toast', [
                'title' => 'Success 🎉',
                'message' => 'SubCategory updated successfully!',
                'type' => 'success'
            ]);

        } else {
            // Create new subcategory with temporary image_path to satisfy NOT NULL
            $subCategory = SubCategory::create([
                'name' => $validatedData['name'],
                'slug' => $validatedData['slug'],
                'status' => $validatedData['status'],
                'category_id' => $validatedData['category_id'] ?? null, // if you have parent category
                'image_path' => 'temp.png', // temporary placeholder
            ]);

            // Handle image upload now
            if ($this->image) {
                $extension = "webp";
                $randomString = substr(md5(uniqid()), 0, 3);
                $time = time();
                $customName = "subctgry-{$subCategory->id}-{$time}-{$randomString}.{$extension}";

                $image = $manager->read($this->image->getRealPath());
                $image->encodeByExtension('webp', 85);

                // $imagePath = $this->image->storeAs('sub_categories', $customName, 'public');
                $path = 'sub_categories/' . $customName;
                Storage::disk('public')->put($path, (string) $image->encode());

                // $subCategory->update(['image_path' => $imagePath]);
                $subCategory->update(['image_path' => $path]);
            }

            $this->subCategoryId = $subCategory->id;
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
        // $this->dispatch('open-modal', name: 'delete-modal');
    }

    public function delete()
    {
        $subCategory = SubCategory::findOrFail($this->subCategoryId);

        if (!empty($subCategory->image_path) && Storage::disk('public')->exists($subCategory->image_path)) {
            Storage::disk('public')->delete($subCategory->image_path);
        }

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
        $this->reset(['subCategoryId', 'category_id', 'name', 'slug', 'status', 'image', 'image_path']);
        $this->status = true;
    }

    public function render()
    {
        $subCategories = SubCategory::with('category')
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
