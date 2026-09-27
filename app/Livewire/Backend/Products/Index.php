<?php

namespace App\Livewire\Backend\Products;

use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductAttribute;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Brand;
use App\Models\Media;

use Flux\Flux;

class Index extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public ?int $productId = null;
    public $category_id = null;
    public $sub_category_id = null;
    public $subcategories = [];
    public ?int $brand_id = null;
    public string $name = '';
    public string $slug = '';
    public ?string $short_description = null;
    public ?string $long_description = null;
    public ?int $media_id = null;
    public ?string $mediaUrl = null;
    public array $existingGallery = [];
    public array $productAttributes = [];
    public bool $status = true;
    public bool $is_featured = false;

    public $visibleColumns = ['id', 'image', 'name', 'category', 'status', 'actions'];

    public $columns = [
        ['key' => 'id', 'label' => 'Id', 'sortable' => true],
        ['key' => 'image', 'label' => 'Image'],
        ['key' => 'name', 'label' => 'Name', 'sortable' => true],
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

    public function toggleColumn($key)
    {
        if (in_array($key, $this->visibleColumns)) {
            $this->visibleColumns = array_diff($this->visibleColumns, [$key]);
        } else {
            $this->visibleColumns[] = $key;
        }
    }

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
            'category_id' => 'required|exists:categories,id',
            'sub_category_id' => 'nullable|exists:sub_categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'name' => 'required|string|max:255',
            'slug' => ['required','string','max:255', $this->productId ? "unique:products,slug,{$this->productId}" : 'unique:products,slug'],
            'short_description' => 'nullable|string|max:500',
            'long_description' => 'nullable|string',
            'media_id' => 'nullable|exists:media,id',
            'status' => 'required|boolean',
            'is_featured' => 'required|boolean',
            'productAttributes.*.color' => 'nullable|string|max:50',
            'productAttributes.*.size' => 'nullable|string|max:50',
            'productAttributes.*.price' => 'required|numeric|min:0',
            'productAttributes.*.offer_price' => 'nullable|numeric|min:0|lt:productAttributes.*.price',
            'productAttributes.*.offer_end_date' => 'nullable|date',
            'productAttributes.*.quantity' => 'required|integer|min:0',
            'productAttributes.*.sku' => [
                'required',
                'string',
                'max:100',
                Rule::unique('product_attributes', 'sku')->where(function ($query) {
                    if ($this->productId) {
                        return $query->where('product_id', '!=', $this->productId);
                    }
                    return $query;
                })
            ],
        ];
    }

    #[On('product-media-selected')]
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

    #[On('product-gallery-media-selected')]
    public function handleGalleryMediaSelected($media): void
    {
        $mediaId = is_array($media) ? ($media['id'] ?? null) : (int) $media;
        if ($mediaId && $this->productId) {
            ProductImage::create([
                'product_id' => $this->productId,
                'media_id'   => $mediaId,
            ]);
            $product = Product::find($this->productId);
            $this->existingGallery = $product ? $product->images()->with('media')->get()->toArray() : [];
        }
    }

    public function updatedName($value)
    {
        $this->slug = Str::slug($value);
    }

    public function resetForm()
    {
        $this->reset([
            'productId','category_id','sub_category_id','brand_id','name','slug',
            'short_description','long_description','media_id','mediaUrl',
            'existingGallery','productAttributes','status','is_featured'
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
        $this->category_id = $product->category_id;
        $this->sub_category_id = $product->sub_category_id;
        $this->brand_id = $product->brand_id;
        $this->name = $product->name;
        $this->slug = $product->slug;
        $this->short_description = $product->short_description;
        $this->long_description = $product->long_description;
        $this->status = (bool) $product->status;
        $this->is_featured = (bool) $product->is_featured;
        $this->media_id = $product->media_id;
        $this->mediaUrl = $product->media?->urls['small'] ?? $product->media?->url;
        $this->existingGallery = $product->images()->with('media')->get()->toArray();
        $this->productAttributes = $product->attributes()->get()->toArray();
        $this->subcategories = SubCategory::where('category_id', $this->category_id)->get();

        Flux::modal('product-modal')->show();
    }

    public function save()
    {
        $validated = $this->validate();

        if ($this->productId) {
            $product = Product::findOrFail($this->productId);
            $product->update($validated);
        } else {
            $product = Product::create($validated);
            $this->productId = $product->id;
        }

        ProductAttribute::where('product_id', $product->id)->delete();
        foreach ($this->productAttributes as $attr) {
            $attr['product_id'] = $product->id;
            ProductAttribute::create($attr);
        }

        $this->dispatch('show-toast',[
            'title'=>'Success 🎉',
            'message'=>'Product saved successfully!',
            'type'=>'success'
        ]);
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
        $product->delete();
        Flux::modal('delete-modal')->close();
        $this->resetForm();
        $this->dispatch('show-toast',['title'=>'Success 🎉','message'=>'Product deleted successfully!','type'=>'success']);
    }

    public function removeGalleryImage($id)
    {
        $img = ProductImage::findOrFail($id);
        $img->delete();
        $this->existingGallery = ProductImage::with('media')->where('product_id', $this->productId)->get()->toArray();
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
        $categories = Category::all();
        $subcategories = SubCategory::where('category_id', $this->category_id)->get();
        $brands = Brand::all();

        if (trim($this->search) === '') {
            $products = Product::with(['category', 'brand', 'attributes', 'media'])
                ->orderBy($this->sortField, $this->sortDirection)
                ->paginate(10);

            return view('livewire.backend.products.index', compact('products', 'categories', 'subcategories', 'brands'));
        } else {
            $attributes = ProductAttribute::with(['product.category', 'product.brand', 'product.media'])
                ->whereHas('product', function ($query) {
                    $query->where('name', 'like', '%' . $this->search . '%');
                })
                ->orWhere('color', 'like', '%' . $this->search . '%')
                ->orWhere('size', 'like', '%' . $this->search . '%')
                ->orWhere('sku', 'like', '%' . $this->search . '%')
                ->orderBy($this->sortField, $this->sortDirection)
                ->paginate(10);

            return view('livewire.backend.products.index', [
                'products' => $attributes,
                'categories' => $categories,
                'subcategories' => $subcategories,
                'brands' => $brands,
            ]);
        }
    }
}
