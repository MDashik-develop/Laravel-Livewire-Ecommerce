<?php

use App\Models\Media;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;

new class extends Component {
    public string $name = '';
    public string $email = '';
    public ?int $media_id = null;
    public ?string $avatarUrl = null;

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->media_id = $user->media_id;
        $this->avatarUrl = $user->avatar_url;
    }

    #[\Livewire\Attributes\On('user-avatar-selected')]
    public function onAvatarSelected($mediaId = null, $url = null, $media = null, ...$rest): void
    {
        $id = $mediaId ?? (is_array($media) ? ($media['id'] ?? null) : null);
        if (!$id && !empty($rest)) {
            $first = $rest[0];
            $id = is_array($first) ? ($first['id'] ?? ($first['mediaId'] ?? null)) : $first;
        }

        $this->media_id = $id ? (int) $id : null;
        if ($url) {
            $this->avatarUrl = $url;
        } elseif ($this->media_id) {
            $mediaModel = Media::find($this->media_id);
            $this->avatarUrl = $mediaModel ? $mediaModel->url : null;
        } else {
            $this->avatarUrl = null;
        }
    }

    public function removeAvatar(): void
    {
        $this->media_id = null;
        $this->avatarUrl = null;
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($user->id)
            ],
            'media_id' => ['nullable', 'integer', 'exists:media,id'],
        ]);

        $user->name = $this->name;
        $user->email = $this->email;
        $user->media_id = $this->media_id;

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $this->avatarUrl = $user->avatar_url;

        $this->dispatch('profile-updated', name: $user->name);
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function resendVerificationNotification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));
            return;
        }

        $user->sendEmailVerificationNotification();
        Session::flash('status', 'verification-link-sent');
    }
}; ?>

<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('Profile')" :subheading="__('Update your personal details, profile photo, and credentials.')">
        <!-- User Member Card & Overview -->
        <div class="mb-8 p-5 bg-gradient-to-br from-indigo-50/70 via-white to-purple-50/50 dark:from-zinc-800/80 dark:via-zinc-800/50 dark:to-indigo-950/20 rounded-2xl border border-indigo-100/80 dark:border-zinc-700 shadow-sm">
            <div class="flex flex-col sm:flex-row items-center sm:items-start gap-4 text-center sm:text-start">
                <!-- Avatar Preview -->
                <div class="relative shrink-0">
                    <div class="w-20 h-20 rounded-2xl overflow-hidden border-2 border-indigo-200 dark:border-zinc-600 bg-indigo-600 text-white font-bold text-2xl flex items-center justify-center shadow-md">
                        @if ($avatarUrl)
                            <img src="{{ $avatarUrl }}" alt="{{ $name }}" class="w-full h-full object-cover">
                        @else
                            <span>{{ auth()->user()->initials() }}</span>
                        @endif
                    </div>
                </div>

                <!-- User Meta Information -->
                <div class="flex-1 min-w-0">
                    <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2">
                        <h3 class="text-base font-bold text-gray-900 dark:text-white">{{ $name }}</h3>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-indigo-100 dark:bg-indigo-950 text-indigo-700 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800">
                            {{ auth()->user()->roles->pluck('name')->join(', ') ?: 'Administrator' }}
                        </span>
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-100 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            Active
                        </span>
                    </div>

                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 truncate">{{ $email }}</p>

                    <!-- Member Since (Requested Feature) -->
                    <div class="mt-3 pt-3 border-t border-gray-200/60 dark:border-zinc-700/60 flex flex-wrap items-center justify-center sm:justify-start gap-4 text-xs text-gray-600 dark:text-gray-300 font-medium">
                        <span class="flex items-center gap-1.5 text-indigo-600 dark:text-indigo-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span><strong>Member Since:</strong> {{ auth()->user()->created_at ? auth()->user()->created_at->format('F d, Y') : 'Recent' }}</span>
                        </span>
                        @if (auth()->user()->created_at)
                            <span class="text-[11px] text-gray-400 dark:text-gray-400">({{ auth()->user()->created_at->diffForHumans() }})</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <form wire:submit="updateProfileInformation" class="my-6 w-full space-y-6">
            <!-- Profile Photo Selector via Media Library -->
            <div class="p-4 bg-gray-50 dark:bg-zinc-800/50 rounded-2xl border border-gray-200 dark:border-zinc-700 space-y-3">
                <label class="block text-xs font-bold text-gray-700 dark:text-gray-300">
                    Profile Photo / Avatar
                </label>

                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-xl overflow-hidden border border-gray-200 dark:border-zinc-600 bg-indigo-600 text-white font-bold text-xl flex items-center justify-center shadow-xs shrink-0">
                        @if ($avatarUrl)
                            <img src="{{ $avatarUrl }}" alt="{{ $name }}" class="w-full h-full object-cover">
                        @else
                            <span>{{ auth()->user()->initials() }}</span>
                        @endif
                    </div>

                    <div class="space-y-1.5 flex-1 min-w-0">
                        <div class="flex items-center gap-2">
                            <button type="button"
                                @click="$dispatch('open-media-modal', { targetEvent: 'user-avatar-selected', type: 'image' })"
                                class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg shadow-xs transition cursor-pointer inline-flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span>{{ $avatarUrl ? 'Change Photo' : 'Select Photo' }}</span>
                            </button>

                            @if ($avatarUrl)
                                <button type="button"
                                    wire:click="removeAvatar"
                                    class="px-3 py-1.5 bg-rose-50 hover:bg-rose-100 dark:bg-rose-950/40 dark:hover:bg-rose-900/60 text-rose-600 dark:text-rose-400 text-xs font-semibold rounded-lg border border-rose-200 dark:border-rose-800 transition cursor-pointer inline-flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    <span>Remove</span>
                                </button>
                            @endif
                        </div>
                        <p class="text-[11px] text-gray-400">Select any image from Media Library. Shows in header and sidebar.</p>
                    </div>
                </div>
                @error('media_id') <span class="text-rose-500 text-xs block">{{ $message }}</span> @enderror
            </div>

            <!-- Name Input -->
            <flux:input wire:model="name" :label="__('Full Name')" type="text" required autofocus autocomplete="name" />

            <!-- Email Input -->
            <div>
                <flux:input wire:model="email" :label="__('Email Address')" type="email" required autocomplete="email" />

                @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! auth()->user()->hasVerifiedEmail())
                    <div class="mt-2">
                        <flux:text class="mt-2">
                            {{ __('Your email address is unverified.') }}

                            <flux:link class="text-sm cursor-pointer" wire:click.prevent="resendVerificationNotification">
                                {{ __('Click here to re-send the verification email.') }}
                            </flux:link>
                        </flux:text>

                        @if (session('status') === 'verification-link-sent')
                            <flux:text class="mt-2 font-medium !dark:text-green-400 !text-green-600">
                                {{ __('A new verification link has been sent to your email address.') }}
                            </flux:text>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Action buttons -->
            <div class="flex items-center gap-4 pt-2">
                <flux:button variant="primary" type="submit" icon="check" class="cursor-pointer shadow-sm px-6">
                    {{ __('Save Changes') }}
                </flux:button>

                <x-action-message class="me-3" on="profile-updated">
                    {{ __('Saved successfully.') }}
                </x-action-message>
            </div>
        </form>

        <livewire:settings.delete-user-form />
    </x-settings.layout>
</section>
