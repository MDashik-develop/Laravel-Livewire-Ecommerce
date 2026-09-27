<?php

namespace App\Livewire\Backend\Banners;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use App\Models\Banner;
use App\Models\Media;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Product;
use Flux\Flux;

class Index extends Component
{
    use WithPagination;

    public $search = '';

    // Form fields
    public ?int $bannerId = null;
    public ?int $media_id = null;
    public ?string $mediaUrl = null;
    public ?int $video_media_id = null;
    public ?string $videoMediaUrl = null;
    public ?string $link = null;
    public ?int $category_id = null;
    public ?int $sub_category_id = null;
    public ?int $product_id = null;
    public int $position = 0;
    public string $section = 'slider';
    public bool $status = true;

    // Available banner placements (extensible)
    public array $availableSections = [
        'slider'   => 'Main Slider',
        'featured' => 'Featured Banner',
        'footer'   => 'Footer Banner',
    ];

    // Delete confirmation
    public ?int $deleteId = null;

    protected function rules(): array
    {
        return [
            'media_id'        => 'required_without:video_media_id|nullable|exists:media,id',
            'video_media_id'  => 'nullable|exists:media,id',
            'link'            => 'nullable|string|max:500',
            'category_id'     => 'nullable|exists:categories,id',
            'sub_category_id' => 'nullable|exists:sub_categories,id',
            'product_id'      => 'nullable|exists:products,id',
            'position'        => 'required|integer|min:0|max:9999',
            'section'         => 'required|string|max:50',
            'status'          => 'required|boolean',
        ];
    }

    protected $messages = [
        'media_id.required_without' => 'Please select at least an Image or a Video for the banner.',
    ];

    #[On('banner-media-selected')]
    public function handleMediaSelected($media): void
    {
        if (is_array($media)) {
            $this->media_id = $media['id'] ?? null;
            $this->mediaUrl = $media['urls']['large'] ?? $media['urls']['small'] ?? $media['url'] ?? null;
        } elseif (is_numeric($media)) {
            $this->media_id = (int) $media;
            $m = Media::find($media);
            $this->mediaUrl = $m?->urls['large'] ?? $m?->urls['small'] ?? $m?->url;
        }
    }

    public function removeMedia(): void
    {
        $this->media_id = null;
        $this->mediaUrl = null;
    }

    #[On('banner-video-selected')]
    public function handleVideoSelected($media): void
    {
        if (is_array($media)) {
            $this->video_media_id = $media['id'] ?? null;
            $this->videoMediaUrl = $media['url'] ?? null;
        } elseif (is_numeric($media)) {
            $this->video_media_id = (int) $media;
            $m = Media::find($media);
            $this->videoMediaUrl = $m?->url;
        }
    }

    public function removeVideoMedia(): void
    {
        $this->video_media_id = null;
        $this->videoMediaUrl = null;
    }

    public function updatedCategoryId($value): void
    {
        $this->sub_category_id = null;
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function resetForm(): void
    {
        $this->reset([
            'bannerId',
            'media_id',
            'mediaUrl',
            'video_media_id',
            'videoMediaUrl',
            'link',
            'category_id',
            'sub_category_id',
            'product_id',
            'position',
            'section',
            'status',
        ]);
        $this->section = 'slider';
        $this->status = true;

        // Auto calculate next position
        $maxPos = Banner::max('position');
        $this->position = $maxPos !== null ? $maxPos + 1 : 0;
    }

    public function edit(int $id): void
    {
        $banner = Banner::with(['media', 'videoMedia'])->findOrFail($id);

        $this->bannerId = $banner->id;
        $this->media_id = $banner->media_id;
        $this->mediaUrl = $banner->media?->urls['large'] ?? $banner->media?->urls['small'] ?? $banner->media?->url;
        $this->video_media_id = $banner->video_media_id;
        $this->videoMediaUrl = $banner->videoMedia?->url;
        $this->link = $banner->link;
        $this->category_id = $banner->category_id;
        $this->sub_category_id = $banner->sub_category_id;
        $this->product_id = $banner->product_id;
        $this->position = (int) $banner->position;
        $this->section = $banner->section ?: 'slider';
        $this->status = (bool) $banner->status;

        Flux::modal('banner-modal')->show();
    }

    public function save(): void
    {
        $validatedData = $this->validate();

        if ($this->bannerId) {
            $banner = Banner::findOrFail($this->bannerId);
            $banner->update($validatedData);

            $this->dispatch('show-toast', [
                'title'   => 'Success 🎉',
                'message' => 'Banner updated successfully!',
                'type'    => 'success',
            ]);
        } else {
            Banner::create($validatedData);

            $this->dispatch('show-toast', [
                'title'   => 'Success 🎉',
                'message' => 'Banner created successfully!',
                'type'    => 'success',
            ]);
        }

        Flux::modal('banner-modal')->close();
        $this->resetForm();
    }

    public function toggleStatus(int $id): void
    {
        $banner = Banner::findOrFail($id);
        $banner->update(['status' => !$banner->status]);

        $this->dispatch('show-toast', [
            'title'   => 'Status Updated',
            'message' => $banner->status ? 'Banner activated!' : 'Banner deactivated!',
            'type'    => 'success',
        ]);
    }

    public function updatePosition(int $id, int $newPos): void
    {
        $banner = Banner::findOrFail($id);
        $banner->update(['position' => max(0, $newPos)]);

        $this->dispatch('show-toast', [
            'title'   => 'Order Updated',
            'message' => 'Banner display order updated!',
            'type'    => 'success',
        ]);
    }

    public function moveUp(int $id): void
    {
        $current = Banner::findOrFail($id);
        $previous = Banner::where('position', '<', $current->position)
            ->orderBy('position', 'desc')
            ->first();

        if ($previous) {
            $temp = $current->position;
            $current->update(['position' => $previous->position]);
            $previous->update(['position' => $temp]);
        } elseif ($current->position > 0) {
            $current->decrement('position');
        }

        $this->dispatch('show-toast', [
            'title'   => 'Reordered',
            'message' => 'Banner moved up in sort order!',
            'type'    => 'success',
        ]);
    }

    public function moveDown(int $id): void
    {
        $current = Banner::findOrFail($id);
        $next = Banner::where('position', '>', $current->position)
            ->orderBy('position', 'asc')
            ->first();

        if ($next) {
            $temp = $current->position;
            $current->update(['position' => $next->position]);
            $next->update(['position' => $temp]);
        } else {
            $current->increment('position');
        }

        $this->dispatch('show-toast', [
            'title'   => 'Reordered',
            'message' => 'Banner moved down in sort order!',
            'type'    => 'success',
        ]);
    }

    public function confirmDelete(int $id): void
    {
        $this->deleteId = $id;
    }

    public function delete(): void
    {
        if ($this->deleteId) {
            $banner = Banner::findOrFail($this->deleteId);
            $banner->delete();

            $this->deleteId = null;
            Flux::modal('delete-modal')->close();

            $this->dispatch('show-toast', [
                'title'   => 'Deleted',
                'message' => 'Banner deleted successfully!',
                'type'    => 'success',
            ]);
        }
    }

    public function render()
    {
        $query = Banner::with(['media', 'videoMedia', 'category', 'sub_category', 'product'])
            ->orderBy('position', 'asc')
            ->orderBy('id', 'desc');

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('link', 'like', '%' . $this->search . '%')
                  ->orWhere('section', 'like', '%' . $this->search . '%')
                  ->orWhereHas('category', fn ($c) => $c->where('name', 'like', '%' . $this->search . '%'))
                  ->orWhereHas('product', fn ($p) => $p->where('name', 'like', '%' . $this->search . '%'));
            });
        }

        $banners = $query->paginate(12);

        $categories = Category::where('status', true)->orderBy('name')->get();
        $subcategories = $this->category_id
            ? SubCategory::where('category_id', $this->category_id)->orderBy('name')->get()
            : collect();
        $products = Product::where('status', true)->select('id', 'name')->orderBy('name')->take(100)->get();

        return view('livewire.backend.banners.index', [
            'banners'           => $banners,
            'categories'        => $categories,
            'subcategories'     => $subcategories,
            'products'          => $products,
            'availableSections' => $this->availableSections,
        ]);
    }
}
