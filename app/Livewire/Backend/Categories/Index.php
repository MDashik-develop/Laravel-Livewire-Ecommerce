<?php

namespace App\Livewire\Backend\Categories;

use Flux\Flux;
use Livewire\Component;
use App\Models\Category;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class Index extends Component
{
    use WithPagination, WithFileUploads;

    //Toast flux massage
    public array $toast = [];
    // Search and filtering properties
    public $search = '';

    // Category properties for form binding
    public ?int $categoryId = null;
    public string $name = '';
    public string $slug = '';
    public bool $status = true;
    public $image; // For new image upload
    public ?string $image_path = null; // For existing image path

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
            'image' => $this->categoryId ? 'nullable|image|max:3072' : 'required|image|max:3072',
        ];
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
        $this->status = $category->status;
        $this->image_path = $category->image_path;
        $this->image = null; // Reset file input
        Flux::modal('category-modal')->show();
    }

    // Method to save or update a category
    public function save()
    {
        $validatedData = $this->validate();

        if ($this->categoryId) {
            // Update existing category
            $category = Category::findOrFail($this->categoryId);

            // Handle image upload
            if ($this->image) {
                if ($category->image_path) {
                    Storage::disk('public')->delete($category->image_path);
                }

                $extension = $this->image->getClientOriginalExtension();
                $randomString = substr(md5(uniqid()), 0, 3);
                $time = time();
                $customName = "ctgry-{$category->id}-{$time}-{$randomString}.{$extension}";

                $validatedData['image_path'] = $this->image->storeAs('categories', $customName, 'public');
            } else {
                $validatedData['image_path'] = $category->image_path;
            }

            $category->update($validatedData);

            $this->dispatch('show-toast', [
                'title' => 'Success 🎉',
                'message' => 'Category updated successfully!',
                'type' => 'success'
            ]);

        } else {
            // Create new category WITHOUT image first to get ID
            $category = Category::create([
                'name' => $validatedData['name'],
                'slug' => $validatedData['slug'],
                'status' => $validatedData['status'],
                'image_path' => 'temp.jpg',
            ]);

            // Handle image upload now
            if ($this->image) {
                $extension = $this->image->getClientOriginalExtension();
                $randomString = substr(md5(uniqid()), 0, 3);
                $time = time();
                $customName = "ctgry-{$category->id}-{$time}-{$randomString}.{$extension}";

                $imagePath = $this->image->storeAs('categories', $customName, 'public');
                $category->update(['image_path' => $imagePath]);
            }

            $this->categoryId = $category->id; // optional, modal/dispatch জন্য

            $this->dispatch('show-toast', [
                'title' => 'Success 🎉',
                'message' => 'Category created successfully!',
                'type' => 'success'
            ]);
        }

        // Close modal and reset form
        $this->dispatch('close-modal', name: 'category-modal');
        Flux::modal('category-modal')->close();
        $this->resetForm();
        $this->resetPage();
    }

    
    // Method to open delete confirmation modal
    public function confirmDelete($id)
    {
        $this->categoryId = $id;
        // $this->dispatch('open-modal', name: 'delete-modal');
    }

    // Method to delete a category
    public function delete()
    {
        $category = Category::findOrFail($this->categoryId);
        
        // Delete image safely
        if (!empty($category->image_path)) {
            // Check if the file actually exists in storage
            if (Storage::disk('public')->exists($category->image_path)) {
                Storage::disk('public')->delete($category->image_path);
            }
        }

        
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
        $this->reset(['categoryId', 'name', 'slug', 'status', 'image', 'image_path']);
        $this->status = true; // Default status
    }

    // The main render method
    public function render()
    {
        $categories = Category::where('name', 'like', '%' . $this->search . '%')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.backend.categories.index', [
            'categories' => $categories,
        ]); 
    }
}