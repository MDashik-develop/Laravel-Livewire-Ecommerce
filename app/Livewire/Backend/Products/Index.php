<?php

namespace App\Livewire\Backend\Products;

use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductAttribute;
use App\Models\Store;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Brand;

use Flux\Flux;

class Index extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public ?int $productId = null;
    public ?int $store_id = null;
    public $category_id = null;
    public $sub_category_id = null;
    public $subcategories = [];
    public ?int $brand_id = null;
    public string $name = '';
    public string $slug = '';
    public ?string $short_description = null;
    public ?string $long_description = null;
    public $thumbnail_image;
    public ?string $thumbnail_path = null;
    public $gallery_images = [];
    public array $existingGallery = [];
    public array $productAttributes = [];
    public bool $status = true;
    public bool $is_featured = false;

    public $visibleColumns = ['id', 'image', 'name', 'store', 'category', 'status', 'actions'];

    public $columns = [
        ['key' => 'id', 'label' => 'Id', 'sortable' => true],
        ['key' => 'image', 'label' => 'Image'],
        ['key' => 'name', 'label' => 'Name', 'sortable' => true],
        ['key' => 'store', 'label' => 'Store'],
        ['key' => 'category', 'label' => 'Category'],
        ['key' => 'brand', 'label' => 'Brand'],
        ['key' => 'sku', 'label' => 'Sku'],
        ['key' => 'size', 'label' => 'Size'],
        ['key' => 'color', 'label' => 'Color'],
        ['key' => 'quantity', 'label' => 'Quantity'],
        ['key' => 'price', 'label' => 'Price'],
        ['key' => 'offer_price', 'label' => 'Offer Price'],
        ['key' => 'offer_end_date', 'label' => 'Offer End Date'],
        ['key' => 'status', 'label' => 'Status'],
        ['key' => 'actions', 'label' => 'Actions'],
    ];

    public $sortField = 'id';
    public $sortDirection = 'desc';

    // Column toggle
    public function toggleColumn($key)
    {
        if (in_array($key, $this->visibleColumns)) {
            $this->visibleColumns = array_diff($this->visibleColumns, [$key]);
        } else {
            $this->visibleColumns[] = $key;
        }
    }

    // Sorting
    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function mount()
    {
        $this->productAttributes[] = [
            'color' => '',
            'size' => '',
            'price' => 0,
            'offer_price' => null,
            'offer_end_date' => null,
            'quantity' => 0,
            'sku' => '',
        ];
    }

    protected function rules()
    {
        return [
            'store_id' => 'required|exists:stores,id',
            'category_id' => 'required|exists:categories,id',
            'sub_category_id' => 'nullable|exists:sub_categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'name' => 'required|string|max:255',
            'slug' => ['required','string','max:255', $this->productId ? "unique:products,slug,{$this->productId}" : 'unique:products,slug'],
            'short_description' => 'nullable|string|max:500',
            'long_description' => 'nullable|string',
            'thumbnail_image' => $this->productId ? 'nullable|image|max:3072' : 'required|image|max:3072',
            'gallery_images.*' => 'image|max:3072',
            'status' => 'required|boolean',
            'is_featured' => 'required|boolean',
            'productAttributes.*.color' => 'nullable|string|max:50',
            'productAttributes.*.size' => 'nullable|string|max:50',
            'productAttributes.*.price' => 'required|numeric|min:0',
            'productAttributes.*.offer_price' => 'nullable|numeric|min:0|lt:productAttributes.*.price',
            'productAttributes.*.offer_end_date' => 'nullable|date|after:today',
            'productAttributes.*.quantity' => 'required|integer|min:0',
            // 'productAttributes.*.sku' => 'required|string|max:100|unique:product_attributes,sku,' . ($this->productId ?? 'NULL'),
            // SKU রুলটি পরিবর্তন করুন
            'productAttributes.*.sku' => [
                'required',
                'string',
                'max:100',
                Rule::unique('product_attributes', 'sku')->where(function ($query) {
                    // যে প্রোডাক্টটি এডিট করা হচ্ছে, তার product_id ছাড়া বাকিদের সাথে unique কিনা চেক করবে
                    if ($this->productId) {
                        return $query->where('product_id', '!=', $this->productId);
                    }
                    return $query;
                })
            ],
        ];
    }

    public function updatedName($value)
    {
        $this->slug = Str::slug($value);
    }

    public function resetForm()
    {
        $this->reset([
            'productId','store_id','category_id','sub_category_id','brand_id','name','slug',
            'short_description','long_description','thumbnail_image','thumbnail_path',
            'gallery_images','existingGallery','productAttributes','status','is_featured'
        ]);
        $this->status = true;
        $this->is_featured = false;
        $this->productAttributes = [
            ['color'=>'','size'=>'','price'=>0,'offer_price'=>null,'offer_end_date'=>null, 'quantity'=>0,'sku'=>'']
        ];
    }

    public function edit(Product $product)
    {
        $this->resetForm();
        $this->productId = $product->id;
        $this->store_id = $product->store_id;
        $this->category_id = $product->category_id;
        $this->sub_category_id = $product->sub_category_id;
        $this->brand_id = $product->brand_id;
        $this->name = $product->name;
        $this->slug = $product->slug;
        $this->short_description = $product->short_description;
        $this->long_description = $product->long_description;
        $this->status = $product->status;
        $this->is_featured = $product->is_featured;
        $this->thumbnail_path = $product->thumbnail_image;
        $this->existingGallery = $product->images()->get(['id','image_path'])->toArray();
        $this->productAttributes = $product->attributes()->get()->toArray();
        $this->subcategories = SubCategory::where('category_id', $this->category_id)->get();

        Flux::modal('product-modal')->show();
    }

    public function save()
    {
        $validated = $this->validate();

        if ($this->thumbnail_image) {
            $name = "product-" . time() . "." . $this->thumbnail_image->getClientOriginalExtension();
            $path = $this->thumbnail_image->storeAs('products', $name, 'public');
            $validated['thumbnail_image'] = $path;

            if ($this->productId && $this->thumbnail_path && Storage::disk('public')->exists($this->thumbnail_path)) {
                Storage::disk('public')->delete($this->thumbnail_path);
            }
        } else {
            $validated['thumbnail_image'] = $this->thumbnail_path ?? null;
        }

        if ($this->productId) {
            $product = Product::findOrFail($this->productId);
            $product->update($validated);
        } else {
            $product = Product::create($validated);
            $this->productId = $product->id;
        }

        if ($this->gallery_images) {
            foreach ($this->gallery_images as $img) {
                $name = "product-gallery-" . time() . "-" . uniqid() . "." . $img->getClientOriginalExtension();
                $path = $img->storeAs('products/gallery', $name, 'public');
                ProductImage::create(['product_id'=>$product->id,'image_path'=>$path]);
            }
        }

        ProductAttribute::where('product_id', $product->id)->delete();
        foreach ($this->productAttributes as $attr) {
            $attr['product_id'] = $product->id;
            ProductAttribute::create($attr);
        }

        $this->dispatch('show-toast',['title'=>'Success 🎉','message'=>'Product saved successfully!','type'=>'success']);
        Flux::modal('product-modal')->close();
        $this->resetForm();
        $this->resetPage();
    }

    public function confirmDelete($id)
    {
        $this->productId = $id;
    }

    public function delete()
    {
        $product = Product::findOrFail($this->productId);

        if ($product->thumbnail_image && Storage::disk('public')->exists($product->thumbnail_image)) {
            Storage::disk('public')->delete($product->thumbnail_image);
        }

        foreach ($product->images as $img) {
            if (Storage::disk('public')->exists($img->image_path)) {
                Storage::disk('public')->delete($img->image_path);
            }
            $img->delete();
        }

        $product->delete();
        Flux::modal('delete-modal')->close();
        $this->resetForm();
        $this->dispatch('show-toast',['title'=>'Success 🎉','message'=>'Product deleted successfully!','type'=>'success']);
    }

    public function removeGalleryImage($id)
    {
        $img = ProductImage::findOrFail($id);
        if (Storage::disk('public')->exists($img->image_path)) {
            Storage::disk('public')->delete($img->image_path);
        }
        $img->delete();
        $this->existingGallery = ProductImage::where('product_id', $this->productId)->get(['id','image_path'])->toArray();
    }

    public function addAttribute()
    {
        $this->productAttributes[] = ['color'=>'','size'=>'','price'=>0,'offer_price'=>null,'offer_end_date'=>null,'quantity'=>0,'sku'=>''];
    }

    public function removeAttribute($index)
    {
        unset($this->productAttributes[$index]);
        $this->productAttributes = array_values($this->productAttributes);
    }

    public function generateSKU($index)
    {
        if (!isset($this->productAttributes[$index])) return;

        $color = $this->productAttributes[$index]['color'] ?? '';
        $size = $this->productAttributes[$index]['size'] ?? '';
        $productId = $this->productId ?? 'NEW';

        $this->productAttributes[$index]['sku'] = ($color || $size) 
            ? strtoupper("PROD{$productId}-".Str::slug($color)."-".Str::slug($size)) 
            : '';
    }

    public function updatedCategoryId($value)
    {
        $this->sub_category_id = null;
        $this->subcategories = SubCategory::where('category_id', $value)->get();
    }

   public function render()
    {
        $stores = Store::where('user_id', Auth::id())->where('status', true)->where('is_approved', true)->get();
        $categories = Category::all();
        $subcategories = SubCategory::where('category_id', $this->category_id)->get();
        $brands = Brand::all();

        // সার্চের ওপর ভিত্তি করে ভিউ এবং ডেটা পরিবর্তন হবে
        if (trim($this->search) === '') {
            // ডিফল্ট ভিউ: সার্চ খালি থাকলে
            $products = Product::with(['store', 'category', 'brand', 'attributes'])
                ->orderBy($this->sortField, $this->sortDirection)
                ->paginate(10);

            return view('livewire.backend.products.index', compact('products', 'stores', 'categories', 'subcategories', 'brands'));
        } else {
            // সার্চ ভিউ: যখন সার্চ করা হবে 
            $attributes = ProductAttribute::with(['product.store', 'product.category', 'product.brand'])
                ->whereHas('product', function ($query) {
                    // প্রোডাক্টের নাম দিয়ে সার্চ
                    $query->where('name', 'like', '%' . $this->search . '%');
                })
                // ভ্যারিয়েন্টের কালার, সাইজ বা SKU দিয়েও সার্চ করা যাবে
                ->orWhere('color', 'like', '%' . $this->search . '%')
                ->orWhere('size', 'like', '%' . $this->search . '%')
                ->orWhere('sku', 'like', '%' . $this->search . '%')
                ->orderBy($this->sortField, $this->sortDirection)
                ->paginate(10);

            return view('livewire.backend.products.index', [
                'products' => $attributes, // এখানে products ভ্যারিয়েবলে attributes পাঠানো হচ্ছে
                'stores' => $stores,
                'categories' => $categories,
                'subcategories' => $subcategories,
                'brands' => $brands,
            ]);
        }
    }
}
