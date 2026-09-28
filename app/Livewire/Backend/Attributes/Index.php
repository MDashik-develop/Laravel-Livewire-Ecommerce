<?php

namespace App\Livewire\Backend\Attributes;

use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Media;
use Flux\Flux;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';

    // Attribute properties
    public ?int $attributeId = null;
    public string $name = '';
    public string $slug = '';
    public string $type = 'text'; // text, color, image
    public bool $is_filterable = false;
    public int $sort_order = 0;

    // Value management modal properties
    public ?int $managingAttributeId = null;
    public ?int $valueId = null;
    public string $valueText = '';
    public ?string $colorCode = '#3B82F6';
    public ?int $valueMediaId = null;
    public ?string $valueMediaUrl = null;
    public int $valueSortOrder = 0;

    public function updatedName($val)
    {
        $this->slug = Str::slug($val);
    }

    public function rules(): array
    {
        return [
            'name'          => 'required|string|max:100',
            'slug'          => [
                'required',
                'string',
                'max:100',
                Rule::unique('attributes', 'slug')->ignore($this->attributeId),
            ],
            'type'          => 'required|in:text,color,image',
            'is_filterable' => 'boolean',
            'sort_order'    => 'integer|min:0',
        ];
    }

    #[On('attribute-value-media-selected')]
    public function handleValueMediaSelected($media): void
    {
        if (is_array($media)) {
            $this->valueMediaId = $media['id'] ?? null;
            $this->valueMediaUrl = $media['urls']['small'] ?? $media['url'] ?? null;
        } elseif (is_numeric($media)) {
            $this->valueMediaId = (int) $media;
            $m = Media::find($media);
            $this->valueMediaUrl = $m?->urls['small'] ?? $m?->url;
        }
    }

    public function removeValueMedia(): void
    {
        $this->valueMediaId = null;
        $this->valueMediaUrl = null;
    }

    public function resetAttributeForm(): void
    {
        $this->reset(['attributeId', 'name', 'slug', 'type', 'is_filterable', 'sort_order']);
        $this->type = 'text';
        $this->sort_order = 0;
        $this->is_filterable = false;
    }

    public function editAttribute(int $id): void
    {
        $attribute = Attribute::findOrFail($id);
        $this->attributeId = $attribute->id;
        $this->name = $attribute->name;
        $this->slug = $attribute->slug;
        $this->type = $attribute->type;
        $this->is_filterable = $attribute->is_filterable;
        $this->sort_order = $attribute->sort_order;

        Flux::modal('attribute-modal')->show();
    }

    public function saveAttribute(): void
    {
        $this->validate();

        Attribute::updateOrCreate(
            ['id' => $this->attributeId],
            [
                'name'          => $this->name,
                'slug'          => $this->slug,
                'type'          => $this->type,
                'is_filterable' => $this->is_filterable,
                'sort_order'    => $this->sort_order,
            ]
        );

        Flux::modal('attribute-modal')->close();
        $this->resetAttributeForm();

        $this->dispatch('show-toast', [
            'title'   => 'Success 🎉',
            'message' => 'Attribute saved successfully!',
            'type'    => 'success',
        ]);
    }

    public function deleteAttribute(int $id): void
    {
        $attribute = Attribute::findOrFail($id);
        $attribute->delete();

        $this->dispatch('show-toast', [
            'title'   => 'Deleted',
            'message' => 'Attribute deleted successfully!',
            'type'    => 'success',
        ]);
    }

    // ==================== Value Management ====================

    public function openValuesModal(int $attributeId): void
    {
        $this->managingAttributeId = $attributeId;
        $this->resetValueForm();
        Flux::modal('attribute-values-modal')->show();
    }

    public function resetValueForm(): void
    {
        $this->reset(['valueId', 'valueText', 'colorCode', 'valueMediaId', 'valueMediaUrl', 'valueSortOrder']);
        $this->colorCode = '#3B82F6';
        $this->valueSortOrder = 0;
    }

    public function editValue(int $valId): void
    {
        $val = AttributeValue::findOrFail($valId);
        $this->valueId = $val->id;
        $this->valueText = $val->value;
        $this->colorCode = $val->color_code ?: '#3B82F6';
        $this->valueMediaId = $val->media_id;
        $this->valueMediaUrl = $val->media?->url;
        $this->valueSortOrder = $val->sort_order;
    }

    public function saveValue(): void
    {
        $this->validate([
            'valueText'       => 'required|string|max:100',
            'colorCode'       => 'nullable|string|max:25',
            'valueMediaId'    => 'nullable|exists:media,id',
            'valueSortOrder'  => 'integer|min:0',
        ]);

        if (!$this->managingAttributeId) return;

        AttributeValue::updateOrCreate(
            ['id' => $this->valueId],
            [
                'attribute_id' => $this->managingAttributeId,
                'value'        => $this->valueText,
                'color_code'   => $this->colorCode,
                'media_id'     => $this->valueMediaId,
                'sort_order'   => $this->valueSortOrder,
            ]
        );

        $this->resetValueForm();

        $this->dispatch('show-toast', [
            'title'   => 'Success',
            'message' => 'Attribute value saved!',
            'type'    => 'success',
        ]);
    }

    public function deleteValue(int $valId): void
    {
        AttributeValue::findOrFail($valId)->delete();

        $this->dispatch('show-toast', [
            'title'   => 'Deleted',
            'message' => 'Attribute value deleted!',
            'type'    => 'success',
        ]);
    }

    public function render()
    {
        $query = Attribute::with(['values.media'])->orderBy('sort_order', 'asc');

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhere('slug', 'like', "%{$this->search}%");
            });
        }

        $attributes = $query->paginate(12);

        $managingAttribute = $this->managingAttributeId ? Attribute::with(['values.media'])->find($this->managingAttributeId) : null;

        return view('livewire.backend.attributes.index', [
            'attributes'        => $attributes,
            'managingAttribute' => $managingAttribute,
        ]);
    }
}
