<?php

namespace App\Livewire\Backend\Products;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\SubCategory;
use Flux\Flux;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public string $sortField = 'id';
    public string $sortDirection = 'desc';
    public array $expandedProducts = [];

    // Delete Modal
    public ?int $productId = null;

    // Stock History Modal
    public ?int $historyProductId = null;
    public ?string $historyProductName = null;
    public $productStockLogs = [];

    // Visible columns in table
    public array $visibleColumns = ['id', 'image', 'name', 'type', 'category', 'brand', 'sku', 'price', 'stock', 'status', 'actions'];

    public array $columns = [
        ['key' => 'id', 'label' => 'ID', 'sortable' => true],
        ['key' => 'image', 'label' => 'Image'],
        ['key' => 'name', 'label' => 'Product Name', 'sortable' => true],
        ['key' => 'type', 'label' => 'Type'],
        ['key' => 'category', 'label' => 'Category'],
        ['key' => 'brand', 'label' => 'Brand'],
        ['key' => 'sku', 'label' => 'Base SKU'],
        ['key' => 'price', 'label' => 'Price'],
        ['key' => 'stock', 'label' => 'Stock'],
        ['key' => 'status', 'label' => 'Status'],
        ['key' => 'actions', 'label' => 'Actions'],
    ];

    public function mount(): void
    {
        if (session()->has('toast')) {
            $this->dispatch('show-toast', session('toast'));
        }
    }

    public function toggleColumn(string $key): void
    {
        if (in_array($key, $this->visibleColumns)) {
            $this->visibleColumns = array_values(array_diff($this->visibleColumns, [$key]));
        } else {
            $this->visibleColumns[] = $key;
        }
    }

    public function toggleExpand(int $productId): void
    {
        if (in_array($productId, $this->expandedProducts)) {
            $this->expandedProducts = array_values(array_diff($this->expandedProducts, [$productId]));
        } else {
            $this->expandedProducts[] = $productId;
        }
    }

    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function confirmDelete(int $id): void
    {
        $this->productId = $id;
        Flux::modal('delete-modal')->show();
    }

    public function delete(): void
    {
        if ($this->productId) {
            DB::beginTransaction();
            try {
                $product = Product::findOrFail($this->productId);

                // Cascade delete associated variant attributes and variants
                foreach ($product->variants as $variant) {
                    $variant->variantAttributes()->delete();
                    $variant->stockLogs()->delete();
                    $variant->delete();
                }
                $product->galleries()->delete();
                $product->stockLogs()->delete();
                $product->delete();

                DB::commit();
                $this->productId = null;
                Flux::modal('delete-modal')->close();

                $this->dispatch('show-toast', [
                    'title'   => 'Deleted',
                    'message' => 'Product deleted successfully!',
                    'type'    => 'success',
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                report($e);
                Log::error('Failed to delete product: ' . $e->getMessage(), [
                    'exception' => $e,
                    'productId' => $this->productId,
                ]);

                $this->dispatch('show-toast', [
                    'title'   => 'Error ❌',
                    'message' => 'Failed to delete product: ' . $e->getMessage(),
                    'type'    => 'danger',
                ]);
            }
        }
    }

    // View Stock History
    public function viewStockHistory(int $productId): void
    {
        $product = Product::with(['stockLogs.user', 'stockLogs.variant'])->findOrFail($productId);
        $this->historyProductId = $product->id;
        $this->historyProductName = $product->name;
        $this->productStockLogs = $product->stockLogs()->with(['user', 'variant.variantAttributes.attributeValue'])->latest('id')->take(50)->get();

        Flux::modal('stock-history-modal')->show();
    }

    public function render()
    {
        // Optimized query with explicit column selection and eager loading
        $query = Product::query()
            ->select([
                'id',
                'category_id',
                'sub_category_id',
                'brand_id',
                'media_id',
                'name',
                'slug',
                'has_variants',
                'sku',
                'barcode',
                'price',
                'cost_price',
                'discount_price',
                'stock',
                'weight',
                'status',
                'is_featured',
                'created_at',
            ])
            ->with([
                'category:id,name',
                'subCategory:id,name',
                'brand:id,name',
                'media:id,path,type,folder,sizes',
                'variants' => function ($vq) {
                    $vq->select([
                        'id',
                        'product_id',
                        'sku',
                        'barcode',
                        'cost_price',
                        'selling_price',
                        'discount_price',
                        'stock',
                        'weight',
                        'media_id',
                        'is_default',
                        'status',
                    ])->with([
                        'media:id,path,type,folder,sizes',
                        'variantAttributes' => function ($vaq) {
                            $vaq->select([
                                'id',
                                'product_variant_id',
                                'attribute_id',
                                'attribute_value_id',
                                'media_id',
                            ])->with([
                                'attribute:id,name,type',
                                'attributeValue:id,attribute_id,value,color_code,media_id',
                                'media:id,path,type,folder,sizes',
                            ]);
                        },
                    ]);
                },
            ]);

        if (!empty($this->search)) {
            $search = trim($this->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('barcode', 'like', "%{$search}%")
                  ->orWhereHas('variants', function ($vq) use ($search) {
                      $vq->where('sku', 'like', "%{$search}%")
                        ->orWhere('barcode', 'like', "%{$search}%")
                        ->orWhereHas('variantAttributes.attributeValue', function ($avq) use ($search) {
                            $avq->where('value', 'like', "%{$search}%");
                        })
                        ->orWhereHas('variantAttributes.attribute', function ($aq) use ($search) {
                            $aq->where('name', 'like', "%{$search}%");
                        });
                  });
            });
        }

        $query->orderBy($this->sortField, $this->sortDirection);

        $products = $query->paginate(12);

        return view('livewire.backend.products.index', [
            'products' => $products,
        ]);
    }
}
