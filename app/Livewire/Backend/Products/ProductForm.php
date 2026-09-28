<?php

namespace App\Livewire\Backend\Products;

use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Media;
use App\Models\Product;
use App\Models\ProductGallery;
use App\Models\ProductVariant;
use App\Models\ProductVariantAttribute;
use App\Models\SubCategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;
use Livewire\Component;

class ProductForm extends Component
{
    // Form fields
    public ?int $productId = null;
    public ?int $category_id = null;
    public ?int $sub_category_id = null;
    public ?int $brand_id = null;
    public string $name = '';
    public string $slug = '';
    public ?string $short_description = null;
    public ?string $long_description = null;
    public ?int $media_id = null;
    public ?string $mediaUrl = null;
    public bool $status = true;
    public bool $is_featured = false;

    // Single vs Variable Product Mode
    public bool $has_variants = false;

    // Single Product attributes
    public ?string $sku = null;
    public ?string $barcode = null;
    public $price = 0;
    public $cost_price = null;
    public $discount_price = null;
    public int $stock = 0;
    public $weight = null;

    // Gallery
    public array $galleryMedia = []; // [['id' => media_id, 'url' => '...']]
    public array $existingGallery = [];

    // Variable Product configuration
    public array $selectedAttributes = []; // [attribute_id => [val_id, val_id]]
    public array $colorMediaMap = []; // [attribute_value_id => ['media_id' => int, 'media_url' => string]]
    public ?int $activeColorValueId = null;
    public array $variants = [];
    public ?int $activeVariantIndex = null;
    public ?int $activeVariantAttrIndex = null;

    public function mount(?int $id = null): void
    {
        if ($id) {
            $this->loadProduct($id);
        } else {
            $this->status = true;
            $this->is_featured = false;
            $this->has_variants = false;
            $this->price = 0;
            $this->stock = 0;
            $prefix = $this->generateBaseBarcodePrefix();
            $this->barcode = $this->generateVariantBarcode($prefix, 0);
        }
    }

    public function loadProduct(int $id): void
    {
        $product = Product::with([
            'variants.variantAttributes.attribute',
            'variants.variantAttributes.attributeValue',
            'variants.media',
            'galleries.media',
            'media'
        ])->findOrFail($id);

        $this->productId = $product->id;
        $this->name = $product->name;
        $this->slug = $product->slug;
        $this->category_id = $product->category_id;
        $this->sub_category_id = $product->sub_category_id;
        $this->brand_id = $product->brand_id;
        $this->short_description = $product->short_description;
        $this->long_description = $product->long_description;
        $this->media_id = $product->media_id;
        $this->mediaUrl = $product->media?->url;
        $this->is_featured = $product->is_featured;
        $this->status = $product->status;
        $this->has_variants = $product->has_variants;

        $this->sku = $product->sku;
        $this->barcode = $product->barcode;
        if (empty($this->barcode)) {
            $prefix = $this->generateBaseBarcodePrefix();
            $this->barcode = $this->generateVariantBarcode($prefix, 0);
        }

        $this->price = (float) $product->price;
        $this->cost_price = $product->cost_price;
        $this->discount_price = $product->discount_price;
        $this->stock = (int) $product->stock;
        $this->weight = $product->weight;

        $this->loadExistingGallery();

        // Load variants if variable product
        $this->variants = [];
        $this->selectedAttributes = [];
        $this->colorMediaMap = [];
        $this->activeColorValueId = null;

        $baseBarcodePrefix = !empty($this->barcode) && strlen($this->barcode) >= 9 
            ? substr($this->barcode, 0, 9) 
            : $this->generateBaseBarcodePrefix();

        if ($product->has_variants) {
            $vSeq = 1;
            foreach ($product->variants as $var) {
                if ($var->is_default) continue;

                $attrItems = [];
                foreach ($var->variantAttributes as $va) {
                    $mediaId = $va->media_id ?: $va->attributeValue?->media_id;
                    $mediaUrl = $va->media?->url ?: $va->attributeValue?->media?->url;

                    if ($va->attribute?->type === 'color' && $mediaId) {
                        $this->colorMediaMap[$va->attribute_value_id] = [
                            'media_id'  => $mediaId,
                            'media_url' => $mediaUrl,
                        ];
                    }

                    $attrItems[] = [
                        'attribute_id'       => $va->attribute_id,
                        'attr_name'          => $va->attribute?->name,
                        'attr_type'          => $va->attribute?->type,
                        'attribute_value_id' => $va->attribute_value_id,
                        'val_name'           => $va->attributeValue?->value,
                        'color_code'         => $va->attributeValue?->color_code,
                        'media_id'           => $mediaId,
                        'media_url'          => $mediaUrl,
                    ];

                    if (!isset($this->selectedAttributes[$va->attribute_id])) {
                        $this->selectedAttributes[$va->attribute_id] = [];
                    }
                    if (!in_array($va->attribute_value_id, $this->selectedAttributes[$va->attribute_id])) {
                        $this->selectedAttributes[$va->attribute_id][] = $va->attribute_value_id;
                    }
                }

                $varBarcode = !empty($var->barcode) ? $var->barcode : $this->generateVariantBarcode($baseBarcodePrefix, $vSeq);
                $vSeq++;

                $this->variants[] = [
                    'id'             => $var->id,
                    'sku'            => $var->sku,
                    'barcode'        => $varBarcode,
                    'cost_price'     => $var->cost_price,
                    'selling_price'  => (float) $var->selling_price,
                    'discount_price' => $var->discount_price,
                    'stock'          => (int) $var->stock,
                    'weight'         => $var->weight,
                    'media_id'       => $var->media_id,
                    'media_url'      => $var->media?->url,
                    'status'         => $var->status,
                    'attributes'     => $attrItems,
                ];
            }
        }
    }

    public function generateBaseBarcodePrefix(): string
    {
        if ($this->productId) {
            $num = 100000 + ($this->productId % 900000);
        } else {
            $seed = abs(crc32($this->name ?: uniqid())) % 900000;
            $num = 100000 + $seed;
        }
        return '890' . str_pad((string) $num, 6, '0', STR_PAD_LEFT);
    }

    public function generateVariantBarcode(string $prefix, int $sequence): string
    {
        return $prefix . str_pad((string) $sequence, 3, '0', STR_PAD_LEFT);
    }

    public function updatedName($val): void
    {
        $this->slug = Str::slug($val);
        if (!$this->has_variants && empty($this->sku)) {
            $this->sku = strtoupper(Str::slug(substr($val, 0, 8))) . '-' . rand(100, 999);
        }
        if (empty($this->barcode)) {
            $prefix = $this->generateBaseBarcodePrefix();
            $this->barcode = $this->generateVariantBarcode($prefix, 0);
        }
    }

    public function updatedCategoryId($val): void
    {
        $this->sub_category_id = null;
    }

    public function setProductType(bool $hasVariants): void
    {
        $this->has_variants = $hasVariants;
        if (!is_array($this->selectedAttributes)) {
            $this->selectedAttributes = [];
        }
    }

    public function toggleAttributeValue(int $attrId, int $valId): void
    {
        if (!is_array($this->selectedAttributes)) {
            $this->selectedAttributes = [];
        }

        if (!isset($this->selectedAttributes[$attrId]) || !is_array($this->selectedAttributes[$attrId])) {
            $this->selectedAttributes[$attrId] = [];
        }

        if (in_array($valId, $this->selectedAttributes[$attrId])) {
            $this->selectedAttributes[$attrId] = array_values(array_diff($this->selectedAttributes[$attrId], [$valId]));
        } else {
            $this->selectedAttributes[$attrId][] = $valId;
        }
    }

    public function updatedHasVariants($val): void
    {
        if (!is_array($this->selectedAttributes)) {
            $this->selectedAttributes = [];
        }
    }

    // Media Handlers
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
        $items = [];
        if (is_array($media) && isset($media[0])) {
            $items = $media;
        } else {
            $items = [$media];
        }

        $addedCount = 0;
        foreach ($items as $item) {
            $mediaId = is_array($item) ? ($item['id'] ?? null) : (int) $item;
            $url = is_array($item) ? ($item['urls']['small'] ?? $item['urls']['thumb'] ?? $item['url'] ?? '') : '';
            if (!$url && $mediaId) {
                $m = Media::find($mediaId);
                $url = $m?->urls['small'] ?? $m?->urls['thumb'] ?? $m?->url ?? '';
            }

            if ($mediaId) {
                if ($this->productId) {
                    ProductGallery::firstOrCreate([
                        'product_id' => $this->productId,
                        'media_id'   => $mediaId,
                    ], [
                        'sort_order' => count($this->existingGallery) + $addedCount + 1,
                    ]);
                    $addedCount++;
                } else {
                    $alreadyQueued = collect($this->galleryMedia)->contains('id', $mediaId);
                    if (!$alreadyQueued) {
                        $this->galleryMedia[] = [
                            'id'  => $mediaId,
                            'url' => $url,
                        ];
                        $addedCount++;
                    }
                }
            }
        }

        if ($this->productId) {
            $this->loadExistingGallery();
        }

        if ($addedCount > 0) {
            $this->dispatch('show-toast', [
                'title'   => 'Gallery Updated',
                'message' => "{$addedCount} photo(s) added to product gallery!",
                'type'    => 'success',
            ]);
        }
    }

    public function removeGalleryImage(int $galleryId): void
    {
        ProductGallery::where('id', $galleryId)->delete();
        $this->loadExistingGallery();
    }

    public function removePendingGalleryItem(int $index): void
    {
        unset($this->galleryMedia[$index]);
        $this->galleryMedia = array_values($this->galleryMedia);
    }

    private function loadExistingGallery(): void
    {
        if ($this->productId) {
            $p = Product::find($this->productId);
            $this->existingGallery = $p ? $p->galleries()->with('media')->get()->toArray() : [];
        }
    }

    // Variant Media Handlers
    public function selectVariantImage(int $index): void
    {
        $this->activeVariantIndex = $index;
        $this->activeVariantAttrIndex = null;
        $currentMediaId = $this->variants[$index]['media_id'] ?? null;
        $this->dispatch('open-media-modal', targetEvent: 'variant-media-selected', type: 'image', selectedId: $currentMediaId);
    }

    public function selectVariantAttrColorImage(int $vIndex, int $attrIndex): void
    {
        $this->activeVariantIndex = $vIndex;
        $this->activeVariantAttrIndex = $attrIndex;
        $currentMediaId = $this->variants[$vIndex]['attributes'][$attrIndex]['media_id'] ?? null;
        $this->dispatch('open-media-modal', targetEvent: 'variant-attr-media-selected', type: 'image', selectedId: $currentMediaId);
    }

    #[On('variant-media-selected')]
    public function handleVariantMediaSelected($media): void
    {
        $mediaId = is_array($media) ? ($media['id'] ?? null) : (int) $media;
        $url = is_array($media) ? ($media['urls']['small'] ?? $media['url'] ?? '') : '';
        if (!$url && $mediaId) {
            $m = Media::find($mediaId);
            $url = $m?->urls['small'] ?? $m?->url ?? '';
        }

        if ($this->activeVariantIndex !== null && isset($this->variants[$this->activeVariantIndex])) {
            $this->variants[$this->activeVariantIndex]['media_id'] = $mediaId;
            $this->variants[$this->activeVariantIndex]['media_url'] = $url;
        }
    }

    #[On('variant-attr-media-selected')]
    public function handleVariantAttrMediaSelected($media): void
    {
        $mediaId = is_array($media) ? ($media['id'] ?? null) : (int) $media;
        $url = is_array($media) ? ($media['urls']['small'] ?? $media['url'] ?? '') : '';
        if (!$url && $mediaId) {
            $m = Media::find($mediaId);
            $url = $m?->urls['small'] ?? $m?->url ?? '';
        }

        if ($this->activeVariantIndex !== null && $this->activeVariantAttrIndex !== null) {
            if (isset($this->variants[$this->activeVariantIndex]['attributes'][$this->activeVariantAttrIndex])) {
                $this->variants[$this->activeVariantIndex]['attributes'][$this->activeVariantAttrIndex]['media_id'] = $mediaId;
                $this->variants[$this->activeVariantIndex]['attributes'][$this->activeVariantAttrIndex]['media_url'] = $url;
            }
        }
    }

    public function removeVariantImage(int $index): void
    {
        if (isset($this->variants[$index])) {
            $this->variants[$index]['media_id'] = null;
            $this->variants[$index]['media_url'] = null;
        }
    }

    public function removeVariantAttrImage(int $vIndex, int $attrIndex): void
    {
        if (isset($this->variants[$vIndex]['attributes'][$attrIndex])) {
            $this->variants[$vIndex]['attributes'][$attrIndex]['media_id'] = null;
            $this->variants[$vIndex]['attributes'][$attrIndex]['media_url'] = null;
        }
    }

    public function selectColorPhoto(int $valueId): void
    {
        $this->activeColorValueId = $valueId;
        $currentMediaId = $this->colorMediaMap[$valueId]['media_id'] ?? null;
        $this->dispatch('open-media-modal', targetEvent: 'color-media-selected', type: 'image', selectedId: $currentMediaId);
    }

    #[On('color-media-selected')]
    public function handleColorMediaSelected($media): void
    {
        $mediaId = is_array($media) ? ($media['id'] ?? null) : (int) $media;
        $url = is_array($media) ? ($media['urls']['small'] ?? $media['urls']['thumb'] ?? $media['url'] ?? '') : '';
        if (!$url && $mediaId) {
            $m = Media::find($mediaId);
            $url = $m?->urls['small'] ?? $m?->urls['thumb'] ?? $m?->url ?? '';
        }

        if ($this->activeColorValueId !== null) {
            $this->colorMediaMap[$this->activeColorValueId] = [
                'media_id'  => $mediaId,
                'media_url' => $url,
            ];

            $this->syncColorMediaToVariants($this->activeColorValueId, $mediaId, $url);
        }
    }

    public function removeColorPhoto(int $valueId): void
    {
        unset($this->colorMediaMap[$valueId]);
        $this->syncColorMediaToVariants($valueId, null, null);
    }

    public function syncColorMediaToVariants(int $colorValId, ?int $mediaId, ?string $url): void
    {
        foreach ($this->variants as $vIndex => $var) {
            foreach ($var['attributes'] as $attrIndex => $attrItem) {
                if (($attrItem['attr_type'] ?? '') === 'color' && (int)$attrItem['attribute_value_id'] === $colorValId) {
                    $this->variants[$vIndex]['attributes'][$attrIndex]['media_id'] = $mediaId;
                    $this->variants[$vIndex]['attributes'][$attrIndex]['media_url'] = $url;
                }
            }
        }
    }

    public function generateVariants(): void
    {
        if (!is_array($this->selectedAttributes)) {
            $this->selectedAttributes = [];
        }

        $filtered = array_filter($this->selectedAttributes, fn($vals) => is_array($vals) && !empty($vals));

        if (empty($filtered)) {
            $this->dispatch('show-toast', [
                'title'   => 'Notice',
                'message' => 'Please select at least one attribute value first!',
                'type'    => 'warning',
            ]);
            return;
        }

        $pools = [];
        foreach ($filtered as $attrId => $valIds) {
            $attr = Attribute::find($attrId);
            if (!$attr) continue;

            $vals = AttributeValue::whereIn('id', $valIds)->get();
            $items = [];
            foreach ($vals as $v) {
                $items[] = [
                    'attribute_id'       => $attr->id,
                    'attr_name'          => $attr->name,
                    'attr_type'          => $attr->type,
                    'attribute_value_id' => $v->id,
                    'val_name'           => $v->value,
                    'color_code'         => $v->color_code,
                    'media_id'           => $v->media_id,
                    'media_url'          => $v->media?->url,
                ];
            }
            if (!empty($items)) {
                $pools[] = $items;
            }
        }

        if (empty($pools)) return;

        $combinations = [[]];
        foreach ($pools as $pool) {
            $append = [];
            foreach ($combinations as $comb) {
                foreach ($pool as $item) {
                    $append[] = array_merge($comb, [$item]);
                }
            }
            $combinations = $append;
        }

        $newVariants = [];
        $baseSku = !empty($this->slug) ? strtoupper($this->slug) : 'PROD';
        $basePrice = (float) $this->price ?: 0;
        $baseCost = $this->cost_price;

        $prefix = !empty($this->barcode) && strlen($this->barcode) >= 9
            ? substr($this->barcode, 0, 9)
            : $this->generateBaseBarcodePrefix();

        // Build a lookup map of existing variants by attribute-value signature
        // Signature = sorted attribute_value_ids joined by "-"
        // e.g. a variant with color=Red(id:3) + size=L(id:7) → "3-7"
        $existingBySignature = [];
        foreach ($this->variants as $existingVar) {
            $sig = collect($existingVar['attributes'] ?? [])
                ->sortBy('attribute_id')
                ->pluck('attribute_value_id')
                ->implode('-');
            if ($sig !== '') {
                $existingBySignature[$sig] = $existingVar;
            }
        }

        $newCount      = 0;
        $preservedCount = 0;

        foreach ($combinations as $idx => $comb) {
            $valNames    = array_map(fn($c) => Str::slug($c['val_name']), $comb);
            $skuSuffix   = strtoupper(implode('-', $valNames));
            $variantSku  = "{$baseSku}-{$skuSuffix}";
            $variantBarcode = $this->generateVariantBarcode($prefix, $idx + 1);

            // Apply color-way media onto the combination attribute items
            foreach ($comb as $k => $c) {
                if (($c['attr_type'] ?? '') === 'color') {
                    $colorValId = (int) $c['attribute_value_id'];
                    if (isset($this->colorMediaMap[$colorValId])) {
                        $comb[$k]['media_id']  = $this->colorMediaMap[$colorValId]['media_id'];
                        $comb[$k]['media_url'] = $this->colorMediaMap[$colorValId]['media_url'];
                    }
                }
            }

            // Build the signature for this new combination
            $newSig = collect($comb)
                ->sortBy('attribute_id')
                ->pluck('attribute_value_id')
                ->implode('-');

            if (isset($existingBySignature[$newSig])) {
                // ✅ Same attribute combo already exists — preserve ALL existing data
                // Only refresh the attributes array (color media may have changed)
                $newVariants[] = array_merge($existingBySignature[$newSig], [
                    'attributes' => $comb,
                ]);
                $preservedCount++;
            } else {
                // 🆕 Brand-new combination — start with defaults
                $newVariants[] = [
                    'id'             => null,
                    'sku'            => $variantSku,
                    'barcode'        => $variantBarcode,
                    'cost_price'     => $baseCost,
                    'selling_price'  => $basePrice,
                    'discount_price' => null,
                    'stock'          => 0,
                    'weight'         => $this->weight,
                    'media_id'       => null,
                    'media_url'      => null,
                    'status'         => true,
                    'attributes'     => $comb,
                ];
                $newCount++;
            }
        }

        $this->variants = $newVariants;

        $msg = count($newVariants) . ' variants total';
        if ($newCount > 0)      $msg .= " — {$newCount} new added";
        if ($preservedCount > 0) $msg .= ", {$preservedCount} existing preserved";

        $this->dispatch('show-toast', [
            'title'   => 'Combinations Updated',
            'message' => $msg . '.',
            'type'    => 'success',
        ]);
    }

    public function removeVariantRow(int $index): void
    {
        unset($this->variants[$index]);
        $this->variants = array_values($this->variants);
    }

    public function save()
    {
        $this->validate([
            'name'           => 'required|string|max:255',
            'slug'           => [
                'required', 'string', 'max:255',
                Rule::unique('products', 'slug')->ignore($this->productId)
            ],
            'category_id'    => 'required|exists:categories,id',
            'sub_category_id'=> 'nullable|exists:sub_categories,id',
            'brand_id'       => 'nullable|exists:brands,id',
            'media_id'       => 'nullable|exists:media,id',
            'price'          => 'required_if:has_variants,false|numeric|min:0',
            'stock'          => 'required_if:has_variants,false|integer|min:0',
            'status'         => 'required|boolean',
            'is_featured'    => 'required|boolean',
        ]);

        DB::beginTransaction();
        try {
            $productData = [
                'name'              => $this->name,
                'slug'              => $this->slug,
                'category_id'       => $this->category_id,
                'sub_category_id'   => $this->sub_category_id,
                'brand_id'          => $this->brand_id,
                'short_description' => $this->short_description,
                'long_description'  => $this->long_description,
                'media_id'          => $this->media_id,
                'has_variants'      => $this->has_variants,
                'status'            => $this->status,
                'is_featured'       => $this->is_featured,
            ];

            if (!$this->has_variants) {
                $productData['sku']            = $this->sku;
                $productData['barcode']        = $this->barcode;
                $productData['price']          = $this->price;
                $productData['cost_price']     = $this->cost_price;
                $productData['discount_price'] = $this->discount_price;
                $productData['stock']          = $this->stock;
                $productData['weight']         = $this->weight;
            }

            $product = Product::updateOrCreate(['id' => $this->productId], $productData);

            // 1. Single Product default variant
            if (!$this->has_variants) {
                $product->variants()->where('is_default', false)->delete();

                ProductVariant::updateOrCreate(
                    ['product_id' => $product->id, 'is_default' => true],
                    [
                        'sku'            => $this->sku,
                        'barcode'        => $this->barcode,
                        'cost_price'     => $this->cost_price,
                        'selling_price'  => $this->price,
                        'discount_price' => $this->discount_price,
                        'stock'          => $this->stock,
                        'weight'         => $this->weight,
                        'media_id'       => $this->media_id,
                        'status'         => $this->status,
                    ]
                );
            } else {
                // 2. Variable Product variants
                $product->variants()->where('is_default', true)->delete();

                $existingVariantIds = [];

                foreach ($this->variants as $varData) {
                    $variantMediaId = $varData['media_id'] ?? null;

                    $variantId = $varData['id'] ?? null;

                    if ($variantId) {
                        // Update existing variant
                        $variant = ProductVariant::where('id', $variantId)
                            ->where('product_id', $product->id)
                            ->first();
                        if (!$variant) {
                            $variant = new ProductVariant(['product_id' => $product->id]);
                        }
                    } else {
                        $variant = new ProductVariant(['product_id' => $product->id]);
                    }

                    $variant->fill([
                        'sku'            => $varData['sku'] ?? null,
                        'barcode'        => $varData['barcode'] ?? null,
                        'cost_price'     => $varData['cost_price'] ?? null,
                        'selling_price'  => $varData['selling_price'] ?? 0,
                        'discount_price' => $varData['discount_price'] ?? null,
                        'stock'          => (int) ($varData['stock'] ?? 0),
                        'weight'         => $varData['weight'] ?? null,
                        'media_id'       => $variantMediaId,
                        'is_default'     => false,
                        'status'         => $varData['status'] ?? true,
                    ])->save();

                    $existingVariantIds[] = $variant->id;

                    $variant->variantAttributes()->delete();
                    if (!empty($varData['attributes'])) {
                        foreach ($varData['attributes'] as $attrItem) {
                            $colorMediaId = null;
                            if (($attrItem['attr_type'] ?? '') === 'color') {
                                $colorValId = (int)$attrItem['attribute_value_id'];
                                $colorMediaId = $this->colorMediaMap[$colorValId]['media_id'] ?? $attrItem['media_id'] ?? null;
                            }

                            ProductVariantAttribute::create([
                                'product_variant_id' => $variant->id,
                                'attribute_id'       => $attrItem['attribute_id'],
                                'attribute_value_id' => $attrItem['attribute_value_id'],
                                'media_id'           => $colorMediaId,
                            ]);
                        }
                    }
                }

                $product->variants()->whereNotIn('id', $existingVariantIds)->delete();

                $totalStock = (int) $product->variants()->sum('stock');
                $minPrice = $product->variants()->min('selling_price');
                $minDiscount = $product->variants()->whereNotNull('discount_price')->min('discount_price');

                $product->updateQuietly([
                    'stock'          => $totalStock,
                    'price'          => $minPrice ?? 0,
                    'discount_price' => $minDiscount,
                ]);
            }

            // 3. Save pending gallery images
            if (!empty($this->galleryMedia)) {
                foreach ($this->galleryMedia as $idx => $gItem) {
                    ProductGallery::firstOrCreate([
                        'product_id' => $product->id,
                        'media_id'   => $gItem['id'],
                    ], [
                        'sort_order' => $idx + 1,
                    ]);
                }
            }

            DB::commit();

            session()->flash('toast', [
                'title'   => 'Success 🎉',
                'message' => 'Product ' . ($this->productId ? 'updated' : 'created') . ' successfully!',
                'type'    => 'success',
            ]);

            return $this->redirect(route('backend.products.index'), navigate: true);
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);
            Log::error('Failed to save product: ' . $e->getMessage(), [
                'exception' => $e,
                'productId' => $this->productId,
            ]);

            $this->dispatch('show-toast', [
                'title'   => 'Error ❌',
                'message' => 'Failed to save product: ' . $e->getMessage(),
                'type'    => 'danger',
            ]);
        }
    }

    public function render()
    {
        $categories = Category::select(['id', 'name'])->orderBy('name')->get();
        $subcategories = $this->category_id ? SubCategory::select(['id', 'name', 'category_id'])->where('category_id', $this->category_id)->orderBy('name')->get() : [];
        $brands = Brand::select(['id', 'name'])->orderBy('name')->get();
        $allAttributes = Attribute::with(['values' => function ($vq) {
            $vq->select(['id', 'attribute_id', 'value', 'color_code', 'media_id'])->with('media:id,path,type,folder,sizes');
        }])->orderBy('sort_order', 'asc')->get();

        return view('livewire.backend.products.form', [
            'categories'    => $categories,
            'subcategories' => $subcategories,
            'brands'        => $brands,
            'allAttributes' => $allAttributes,
        ]);
    }
}
