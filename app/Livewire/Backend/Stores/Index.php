<?php

namespace App\Livewire\Backend\Stores;

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Str;
use App\Models\Store;
use App\Models\User;
use App\Models\Media;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    public string $activeTab = 'general';

    // Store Primary Details
    public ?int $storeId = null;
    public ?int $user_id = null;
    public string $name = '';
    public string $slug = '';
    public ?string $description = null;
    public string $phone = '';
    public ?string $email = null;
    public string $address = '';
    public bool $status = true;
    public bool $is_approved = true;

    // Media
    public ?int $media_id = null;
    public ?string $mediaUrl = null;
    public ?int $fav_media_id = null;
    public ?string $favMediaUrl = null;
    public ?int $og_media_id = null;
    public ?string $ogMediaUrl = null;

    // SEO
    public ?string $meta_title = null;
    public ?string $meta_description = null;
    public ?string $meta_keywords = null;
    public ?string $canonical_url = null;
    public ?string $sitemap = null;
    public ?string $twitter_title = null;
    public ?string $twitter_description = null;

    // Marketing & Tracking
    public ?string $meta_pixel_id = null;
    public ?string $google_ads_id = null;
    public ?string $google_analytics_id = null;

    // Social Links
    public ?string $facebook_url = null;
    public ?string $instagram_url = null;
    public ?string $youtube_url = null;

    // Policy & Content Pages (Summernote)
    public ?string $about_page = null;
    public ?string $contact_page = null;
    public ?string $privacy_page = null;
    public ?string $return_page = null;
    public ?string $terms_page = null;

    public function mount(): void
    {
        $this->loadDefaultStore();
    }

    public function loadDefaultStore(): void
    {
        $store = Store::with(['media', 'favMedia', 'ogMedia'])->first();

        if (!$store) {
            $user = Auth::user() ?? User::first();
            if (!$user) {
                $user = User::create([
                    'name'     => 'Store Admin',
                    'email'    => 'admin@example.com',
                    'password' => bcrypt('password'),
                ]);
            }

            $store = Store::create([
                'user_id'     => $user->id,
                'name'        => 'Default Store',
                'slug'        => 'default-store',
                'description' => 'Welcome to our official store.',
                'phone'       => '+8801700000000',
                'address'     => 'Dhaka, Bangladesh',
                'media_id'    => null,
                'is_approved' => true,
                'status'      => true,
            ]);
        }

        $this->storeId              = $store->id;
        $this->user_id              = $store->user_id;
        $this->name                 = $store->name ?? '';
        $this->slug                 = $store->slug ?? '';
        $this->description          = $store->description;
        $this->phone                = $store->phone ?? '';
        $this->email                = $store->email;
        $this->address              = $store->address ?? '';
        $this->status               = (bool) $store->status;
        $this->is_approved          = (bool) $store->is_approved;

        // Media URLs
        $this->media_id             = $store->media_id;
        $this->mediaUrl             = $store->media?->urls['small'] ?? $store->media?->urls['large'] ?? $store->media?->url;
        $this->fav_media_id         = $store->fav_media_id;
        $this->favMediaUrl          = $store->favMedia?->urls['small'] ?? $store->favMedia?->urls['thumb'] ?? $store->favMedia?->url;
        $this->og_media_id          = $store->og_media_id;
        $this->ogMediaUrl           = $store->ogMedia?->urls['small'] ?? $store->ogMedia?->urls['large'] ?? $store->ogMedia?->url;

        // SEO
        $this->meta_title           = $store->meta_title;
        $this->meta_description     = $store->meta_description;
        $this->meta_keywords        = $store->meta_keywords;
        $this->canonical_url        = $store->canonical_url;
        $this->sitemap              = $store->sitemap;
        $this->twitter_title        = $store->twitter_title;
        $this->twitter_description  = $store->twitter_description;

        // Marketing / Tracking
        $this->meta_pixel_id        = $store->meta_pixel_id;
        $this->google_ads_id        = $store->google_ads_id;
        $this->google_analytics_id  = $store->google_analytics_id;

        // Social
        $this->facebook_url         = $store->facebook_url;
        $this->instagram_url        = $store->instagram_url;
        $this->youtube_url          = $store->youtube_url;

        // Pages
        $this->about_page           = $store->about_page;
        $this->contact_page         = $store->contact_page;
        $this->privacy_page         = $store->privacy_page;
        $this->return_page          = $store->return_page;
        $this->terms_page           = $store->terms_page;
    }

    protected function rules(): array
    {
        return [
            'user_id'             => 'required|exists:users,id',
            'name'                => 'required|string|min:2|max:255',
            'slug'                => [
                'required',
                'string',
                'max:255',
                $this->storeId ? "unique:stores,slug,{$this->storeId}" : 'unique:stores,slug',
            ],
            'description'         => 'nullable|string',
            'phone'               => 'required|string|max:30',
            'email'               => 'nullable|email|max:255',
            'address'             => 'required|string|max:500',
            'media_id'            => 'nullable|exists:media,id',
            'fav_media_id'        => 'nullable|exists:media,id',
            'og_media_id'         => 'nullable|exists:media,id',
            'meta_title'          => 'nullable|string|max:255',
            'meta_description'    => 'nullable|string',
            'meta_keywords'       => 'nullable|string|max:500',
            'canonical_url'       => 'nullable|string|max:500',
            'sitemap'             => 'nullable|string|max:500',
            'twitter_title'       => 'nullable|string|max:255',
            'twitter_description' => 'nullable|string',
            'meta_pixel_id'       => 'nullable|string|max:100',
            'google_ads_id'       => 'nullable|string|max:100',
            'google_analytics_id' => 'nullable|string|max:100',
            'facebook_url'        => 'nullable|string|max:500',
            'instagram_url'       => 'nullable|string|max:500',
            'youtube_url'         => 'nullable|string|max:500',
            'about_page'          => 'nullable|string',
            'contact_page'        => 'nullable|string',
            'privacy_page'        => 'nullable|string',
            'return_page'         => 'nullable|string',
            'terms_page'          => 'nullable|string',
            'status'              => 'required|boolean',
            'is_approved'         => 'required|boolean',
        ];
    }

    public function updatedName($value): void
    {
        $this->slug = Str::slug($value);
    }

    // Media Handlers
    #[On('store-media-selected')]
    public function handleMediaSelected($media): void
    {
        if (is_array($media)) {
            $this->media_id = $media['id'] ?? null;
            $this->mediaUrl = $media['urls']['small'] ?? $media['urls']['large'] ?? $media['url'] ?? null;
        } elseif (is_numeric($media)) {
            $this->media_id = (int) $media;
            $m = Media::find($media);
            $this->mediaUrl = $m?->urls['small'] ?? $m?->urls['large'] ?? $m?->url;
        }
    }

    public function removeMedia(): void
    {
        $this->media_id = null;
        $this->mediaUrl = null;
    }

    #[On('store-fav-media-selected')]
    public function handleFavMediaSelected($media): void
    {
        if (is_array($media)) {
            $this->fav_media_id = $media['id'] ?? null;
            $this->favMediaUrl = $media['urls']['small'] ?? $media['urls']['thumb'] ?? $media['url'] ?? null;
        } elseif (is_numeric($media)) {
            $this->fav_media_id = (int) $media;
            $m = Media::find($media);
            $this->favMediaUrl = $m?->urls['small'] ?? $m?->urls['thumb'] ?? $m?->url;
        }
    }

    public function removeFavMedia(): void
    {
        $this->fav_media_id = null;
        $this->favMediaUrl = null;
    }

    #[On('store-og-media-selected')]
    public function handleOgMediaSelected($media): void
    {
        if (is_array($media)) {
            $this->og_media_id = $media['id'] ?? null;
            $this->ogMediaUrl = $media['urls']['small'] ?? $media['urls']['large'] ?? $media['url'] ?? null;
        } elseif (is_numeric($media)) {
            $this->og_media_id = (int) $media;
            $m = Media::find($media);
            $this->ogMediaUrl = $m?->urls['small'] ?? $m?->urls['large'] ?? $m?->url;
        }
    }

    public function removeOgMedia(): void
    {
        $this->og_media_id = null;
        $this->ogMediaUrl = null;
    }

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
    }

    public function save(): void
    {
        try {
            foreach (['email', 'description', 'meta_title', 'meta_description', 'meta_keywords', 'canonical_url', 'sitemap', 'twitter_title', 'twitter_description', 'meta_pixel_id', 'google_ads_id', 'google_analytics_id', 'facebook_url', 'instagram_url', 'youtube_url', 'about_page', 'contact_page', 'privacy_page', 'return_page', 'terms_page'] as $f) {
                if (is_string($this->{$f}) && trim($this->{$f}) === '') {
                    $this->{$f} = null;
                }
            }

            $validated = $this->validate();

            $store = Store::findOrFail($this->storeId);
            $store->update($validated);

            $this->dispatch('show-toast', [
                'title'   => 'Success 🎉',
                'message' => 'Store details, SEO, and pages updated successfully!',
                'type'    => 'success',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $firstError = collect($e->errors())->flatten()->first();
            $this->dispatch('show-toast', [
                'title'   => 'Validation Notice ⚠️',
                'message' => $firstError ?: 'Please check the required fields in the form tabs.',
                'type'    => 'error',
            ]);
            throw $e;
        }
    }

    public function render()
    {
        $users = User::orderBy('name')->get();

        return view('livewire.backend.stores.index', [
            'users' => $users,
        ]);
    }
}
