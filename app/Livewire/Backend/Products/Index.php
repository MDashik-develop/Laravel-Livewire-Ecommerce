<?php

namespace App\Livewire\Backend\Products;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\SubCategory;
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

    // Filters
    public string $selectedCategory = '';
    public string $selectedBrand = '';
    public string $selectedStatus = '';
    public string $selectedStock = '';
    public string $selectedType = '';
    public bool $viewTrash = false;
    public int $perPage = 15;

    // Selection
    public array $selectedProducts = [];
    public bool $selectAll = false;

    // Delete Modal
    public ?int $productId = null;

    // Status Modal
    public ?int $statusModalProductId = null;
    public ?string $statusModalProductName = null;
    public ?string $statusModalProductSku = null;
    public ?string $statusModalProductImage = null;
    public ?float $statusModalProductPrice = null;
    public ?int $statusModalProductStock = null;
    public int $statusModalSelectedStatus = 1;
    public bool $statusModalSyncVariants = true;

    // Stock History Modal
    public ?int $historyProductId = null;
    public ?string $historyProductName = null;
    public $productStockLogs = [];

    // Visible columns in table
    public array $visibleColumns = ['id', 'image', 'name', 'type', 'category', 'brand', 'sku', 'price', 'stock', 'status', 'actions'];

    public array $columns = [
        ['key' => 'id', 'label' => 'ID', 'icon' => 'hash', 'sortable' => true],
        ['key' => 'image', 'label' => 'Image', 'icon' => 'image'],
        ['key' => 'name', 'label' => 'Product Name', 'icon' => 'shopping-bag', 'sortable' => true],
        ['key' => 'type', 'label' => 'Type', 'icon' => 'layers'],
        ['key' => 'category', 'label' => 'Category', 'icon' => 'folder-tree'],
        ['key' => 'brand', 'label' => 'Brand', 'icon' => 'tag'],
        ['key' => 'sku', 'label' => 'Base SKU', 'icon' => 'barcode'],
        ['key' => 'price', 'label' => 'Price', 'icon' => 'dollar-sign'],
        ['key' => 'stock', 'label' => 'Stock', 'icon' => 'boxes'],
        ['key' => 'status', 'label' => 'Status', 'icon' => 'toggle-right'],
        ['key' => 'actions', 'label' => 'Actions', 'icon' => 'more-horizontal'],
    ];

    public function mount(): void
    {
        if (session()->has('toast')) {
            $this->dispatch('show-toast', session('toast'));
        }
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
        $this->selectedProducts = [];
        $this->selectAll = false;
    }

    public function updatingSelectedCategory(): void
    {
        $this->resetPage();
        $this->selectedProducts = [];
        $this->selectAll = false;
    }

    public function updatingSelectedBrand(): void
    {
        $this->resetPage();
        $this->selectedProducts = [];
        $this->selectAll = false;
    }

    public function updatingSelectedStatus(): void
    {
        $this->resetPage();
        $this->selectedProducts = [];
        $this->selectAll = false;
    }

    public function updatingSelectedStock(): void
    {
        $this->resetPage();
        $this->selectedProducts = [];
        $this->selectAll = false;
    }

    public function updatingSelectedType(): void
    {
        $this->resetPage();
        $this->selectedProducts = [];
        $this->selectAll = false;
    }

    public function updatingViewTrash(): void
    {
        $this->resetPage();
        $this->selectedProducts = [];
        $this->selectAll = false;
    }

    public function toggleColumn(string $key): void
    {
        if (in_array($key, $this->visibleColumns)) {
            $this->visibleColumns = array_values(array_diff($this->visibleColumns, [$key]));
        } else {
            $this->visibleColumns[] = $key;
        }
    }

    public function showAllColumns(): void
    {
        $this->visibleColumns = array_column($this->columns, 'key');
    }

    public function resetColumns(): void
    {
        $this->visibleColumns = ['id', 'image', 'name', 'type', 'category', 'brand', 'sku', 'price', 'stock', 'status', 'actions'];
    }

    public function resetFilters(): void
    {
        $this->search = '';
        $this->selectedCategory = '';
        $this->selectedBrand = '';
        $this->selectedStatus = '';
        $this->selectedStock = '';
        $this->selectedType = '';
        $this->resetPage();
        $this->selectedProducts = [];
        $this->selectAll = false;
    }

    public function getActiveFiltersCountProperty(): int
    {
        $count = 0;
        if (!empty(trim($this->search))) $count++;
        if (!empty($this->selectedCategory)) $count++;
        if (!empty($this->selectedBrand)) $count++;
        if (!empty($this->selectedStock)) $count++;
        if ($this->selectedStatus !== '') $count++;
        if (!empty($this->selectedType)) $count++;
        return $count;
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

    public function openStatusModal(int $id): void
    {
        $product = Product::with(['media', 'variants'])->findOrFail($id);
        $this->statusModalProductId = $product->id;
        $this->statusModalProductName = $product->name;
        $this->statusModalProductSku = $product->sku ?: ($product->variants->first()?->sku ?? '-');
        $this->statusModalSelectedStatus = $product->status ? 1 : 0;
        $this->statusModalSyncVariants = true;
        $this->statusModalProductPrice = (float) $product->price;
        $this->statusModalProductStock = (int) ($product->has_variants ? $product->variants->sum('stock') : $product->stock);

        $thumb = $product->media ? ($product->media->urls['thumb'] ?? $product->media->urls['small'] ?? $product->media->url) : null;
        $this->statusModalProductImage = $thumb;

        $this->dispatch('modal-show', name: 'product-status-modal');
    }

    public function saveProductStatus(): void
    {
        if (!$this->statusModalProductId) return;

        DB::beginTransaction();
        try {
            $product = Product::findOrFail($this->statusModalProductId);
            $newStatus = (bool) $this->statusModalSelectedStatus;

            $product->update(['status' => $newStatus]);
            if ($this->statusModalSyncVariants) {
                $product->variants()->update(['status' => $newStatus]);
            }

            DB::commit();

            $this->dispatch('modal-close', name: 'product-status-modal');
            $this->dispatch('show-toast', [
                'title'   => 'Status Updated',
                'message' => "Product '{$product->name}' status set to " . ($newStatus ? 'Active' : 'Inactive'),
                'type'    => 'success',
            ]);
            $this->statusModalProductId = null;
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            $this->dispatch('show-toast', [
                'title'   => 'Error ❌',
                'message' => 'Failed to update status: ' . $e->getMessage(),
                'type'    => 'danger',
            ]);
        }
    }

    public function moveProductToTrashFromModal(): void
    {
        if (!$this->statusModalProductId) return;

        DB::beginTransaction();
        try {
            $product = Product::findOrFail($this->statusModalProductId);
            $name = $product->name;
            $product->delete();

            DB::commit();

            $this->dispatch('modal-close', name: 'product-status-modal');
            $this->dispatch('show-toast', [
                'title'   => 'Moved to Trash',
                'message' => "Product '{$name}' moved to trash successfully.",
                'type'    => 'warning',
            ]);
            $this->statusModalProductId = null;
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            $this->dispatch('show-toast', [
                'title'   => 'Error ❌',
                'message' => 'Failed to move to trash: ' . $e->getMessage(),
                'type'    => 'danger',
            ]);
        }
    }

    public function toggleProductStatus(int $id): void
    {
        $product = Product::withTrashed()->findOrFail($id);
        $newStatus = !$product->status;
        $product->update(['status' => $newStatus]);
        $product->variants()->update(['status' => $newStatus]);

        $this->dispatch('show-toast', [
            'title'   => 'Status Updated',
            'message' => "Product '{$product->name}' is now " . ($newStatus ? 'Active' : 'Inactive'),
            'type'    => 'success',
        ]);
    }

    public function confirmDelete(int $id): void
    {
        $this->productId = $id;
        $this->dispatch('modal-show', name: 'delete-modal');
    }

    public function delete(): void
    {
        if ($this->productId) {
            DB::beginTransaction();
            try {
                $product = Product::withTrashed()->findOrFail($this->productId);

                if ($this->viewTrash) {
                    // Force delete along with variants and associations
                    $product->forceDelete();
                    $msg = 'Product permanently deleted!';
                } else {
                    // Soft delete (cascade hooks in Product model also soft delete variants)
                    $product->delete();
                    $msg = 'Product moved to trash!';
                }

                DB::commit();
                $this->productId = null;
                $this->dispatch('modal-close', name: 'delete-modal');

                $this->dispatch('show-toast', [
                    'title'   => 'Success',
                    'message' => $msg,
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

    public function restore(int $id): void
    {
        $product = Product::onlyTrashed()->findOrFail($id);
        $product->restore();

        $this->dispatch('show-toast', [
            'title'   => 'Restored',
            'message' => "Product '{$product->name}' and its variants were restored!",
            'type'    => 'success',
        ]);
    }

    // ==========================================
    // BULK ACTIONS
    // ==========================================

    public function clearSelection(): void
    {
        $this->selectedProducts = [];
        $this->selectAll = false;
    }

    public function bulkUpdateStatus(bool $status): void
    {
        if (empty($this->selectedProducts)) return;

        DB::beginTransaction();
        try {
            Product::whereIn('id', $this->selectedProducts)->update(['status' => $status]);
            ProductVariant::whereIn('product_id', $this->selectedProducts)->update(['status' => $status]);
            DB::commit();

            $count = count($this->selectedProducts);
            $this->clearSelection();

            $this->dispatch('show-toast', [
                'title'   => 'Bulk Updated',
                'message' => "Successfully updated status for {$count} products to " . ($status ? 'Active' : 'Inactive'),
                'type'    => 'success',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            $this->dispatch('show-toast', [
                'title'   => 'Error ❌',
                'message' => 'Failed to update products status: ' . $e->getMessage(),
                'type'    => 'danger',
            ]);
        }
    }

    public function bulkDelete(): void
    {
        if (empty($this->selectedProducts)) return;

        DB::beginTransaction();
        try {
            $count = count($this->selectedProducts);

            if ($this->viewTrash) {
                // Permanently delete
                Product::onlyTrashed()->whereIn('id', $this->selectedProducts)->get()->each(function ($p) {
                    $p->forceDelete();
                });
                $msg = "{$count} products permanently erased from database.";
            } else {
                // Soft delete
                Product::whereIn('id', $this->selectedProducts)->get()->each(function ($p) {
                    $p->delete();
                });
                $msg = "{$count} products and their variants moved to Trash.";
            }

            DB::commit();
            $this->clearSelection();
            $this->dispatch('modal-close', name: 'bulk-delete-modal');

            $this->dispatch('show-toast', [
                'title'   => 'Bulk Delete Successful',
                'message' => $msg,
                'type'    => 'success',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            $this->dispatch('show-toast', [
                'title'   => 'Error ❌',
                'message' => 'Failed to bulk delete products: ' . $e->getMessage(),
                'type'    => 'danger',
            ]);
        }
    }

    public function bulkRestore(): void
    {
        if (empty($this->selectedProducts)) return;

        DB::beginTransaction();
        try {
            $count = count($this->selectedProducts);
            Product::onlyTrashed()->whereIn('id', $this->selectedProducts)->get()->each(function ($p) {
                $p->restore();
            });
            DB::commit();

            $this->clearSelection();

            $this->dispatch('show-toast', [
                'title'   => 'Bulk Restored',
                'message' => "Successfully restored {$count} products and their variants!",
                'type'    => 'success',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            $this->dispatch('show-toast', [
                'title'   => 'Error ❌',
                'message' => 'Failed to restore products: ' . $e->getMessage(),
                'type'    => 'danger',
            ]);
        }
    }

    // View Stock History
    public function viewStockHistory(int $productId): void
    {
        $product = Product::withTrashed()->with(['stockLogs.user', 'stockLogs.variant'])->findOrFail($productId);
        $this->historyProductId = $product->id;
        $this->historyProductName = $product->name;
        $this->productStockLogs = $product->stockLogs()->with(['user', 'variant.variantAttributes.attributeValue'])->latest('id')->take(50)->get();

        $this->dispatch('modal-show', name: 'stock-history-modal');
    }

    public function render()
    {
        $baseQuery = $this->viewTrash ? Product::onlyTrashed() : Product::query();

        // Counts for tab badges
        $activeCount = Product::count();
        $trashedCount = Product::onlyTrashed()->count();

        // Optimized query with explicit column selection and eager loading
        $query = $baseQuery
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
                'deleted_at',
            ])
            ->with([
                'category:id,name',
                'subCategory:id,name',
                'brand:id,name',
                'media:id,path,type,folder,sizes',
                'variants' => function ($vq) {
                    $vq->withTrashed()->select([
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
                        'deleted_at',
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

        // Search Filter
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

        // Category Filter
        if (!empty($this->selectedCategory)) {
            $query->where('category_id', $this->selectedCategory);
        }

        // Brand Filter
        if (!empty($this->selectedBrand)) {
            $query->where('brand_id', $this->selectedBrand);
        }

        // Status Filter
        if ($this->selectedStatus !== '') {
            $query->where('status', (bool) $this->selectedStatus);
        }

        // Product Type Filter
        if ($this->selectedType === 'single') {
            $query->where('has_variants', false);
        } elseif ($this->selectedType === 'variable') {
            $query->where('has_variants', true);
        }

        // Stock Filter
        if ($this->selectedStock === 'in_stock') {
            $query->where('stock', '>', 5);
        } elseif ($this->selectedStock === 'low_stock') {
            $query->whereBetween('stock', [1, 5]);
        } elseif ($this->selectedStock === 'out_of_stock') {
            $query->where('stock', '<=', 0);
        }

        $query->orderBy($this->sortField, $this->sortDirection);

        $products = $query->paginate($this->perPage);

        $categories = Category::select('id', 'name')->orderBy('name')->get();
        $brands = Brand::select('id', 'name')->orderBy('name')->get();

        return view('livewire.backend.products.index', [
            'products'     => $products,
            'categories'   => $categories,
            'brands'       => $brands,
            'activeCount'  => $activeCount,
            'trashedCount' => $trashedCount,
        ]);
    }
}
