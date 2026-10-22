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
        <div class="mb-6 p-4 bg-slate-50 dark:bg-zinc-900/60 rounded-[4px] border border-slate-200 dark:border-zinc-800">
            <div class="flex flex-col sm:flex-row items-center sm:items-start gap-4 text-center sm:text-start">
                <!-- Avatar Preview -->
                <div class="relative shrink-0">
                    <div class="w-16 h-16 rounded-[3px] overflow-hidden border border-slate-200 dark:border-zinc-700 bg-brand-600 text-white font-bold text-xl flex items-center justify-center shadow-xs">
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
                        <h3 class="text-sm font-bold text-slate-900 dark:text-zinc-100">{{ $name }}</h3>
                        <x-admin.badge variant="primary">
                            {{ auth()->user()->roles->pluck('name')->join(', ') ?: 'Administrator' }}
                        </x-admin.badge>
                        <x-admin.badge variant="success" dot>
                            Active
                        </x-admin.badge>
                    </div>

                    <p class="text-xs text-slate-500 dark:text-zinc-400 mt-0.5 truncate">{{ $email }}</p>

                    <!-- Member Since -->
                    <div class="mt-2.5 pt-2.5 border-t border-slate-200 dark:border-zinc-800 flex flex-wrap items-center justify-center sm:justify-start gap-3 text-xs text-slate-600 dark:text-zinc-400">
                        <span class="flex items-center gap-1.5 text-brand-600 dark:text-brand-400">
                            <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                            <span><strong>Member Since:</strong> {{ auth()->user()->created_at ? auth()->user()->created_at->format('F d, Y') : 'Recent' }}</span>
                        </span>
                        @if (auth()->user()->created_at)
                            <span class="text-[11px] text-slate-400">({{ auth()->user()->created_at->diffForHumans() }})</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <form wire:submit="updateProfileInformation" class="my-5 w-full space-y-4">
            <!-- Profile Photo Selector via Media Library -->
            <div class="p-3.5 bg-slate-50/50 dark:bg-zinc-900/40 rounded-[4px] border border-slate-200 dark:border-zinc-800 space-y-2.5">
                <label class="block text-xs font-semibold text-slate-700 dark:text-zinc-300">
                    Profile Photo / Avatar
                </label>

                <div class="flex items-center gap-3">
                    <div class="w-14 h-14 rounded-[3px] overflow-hidden border border-slate-200 dark:border-zinc-700 bg-brand-600 text-white font-bold text-lg flex items-center justify-center shadow-xs shrink-0">
                        @if ($avatarUrl)
                            <img src="{{ $avatarUrl }}" alt="{{ $name }}" class="w-full h-full object-cover">
                        @else
                            <span>{{ auth()->user()->initials() }}</span>
                        @endif
                    </div>

                    <div class="space-y-1 flex-1 min-w-0">
                        <div class="flex items-center gap-2">
                            <button type="button"
                                @click="$dispatch('open-media-modal', { targetEvent: 'user-avatar-selected', type: 'image' })"
                                class="px-2.5 py-1.5 bg-brand-600 hover:bg-brand-700 text-white text-xs font-semibold rounded-[3px] transition cursor-pointer inline-flex items-center gap-1">
                                <i data-lucide="image-plus" class="w-3.5 h-3.5"></i>
                                <span>{{ $avatarUrl ? 'Change Photo' : 'Select Photo' }}</span>
                            </button>

                            @if ($avatarUrl)
                                <button type="button"
                                    wire:click="removeAvatar"
                                    class="px-2.5 py-1.5 text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/40 text-xs font-semibold rounded-[3px] border border-rose-200 dark:border-rose-800 transition cursor-pointer inline-flex items-center gap-1">
                                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                    <span>Remove</span>
                                </button>
                            @endif
                        </div>
                        <p class="text-[10px] text-slate-400">Select any image from Media Library. Shows in header and sidebar.</p>
                    </div>
                </div>
                @error('media_id') <span class="text-rose-500 text-xs block">{{ $message }}</span> @enderror
            </div>

            <!-- Name Input -->
            <x-admin.input wire:model="name" :label="__('Full Name')" type="text" required autofocus autocomplete="name" />

            <!-- Email Input -->
            <div>
                <x-admin.input wire:model="email" :label="__('Email Address')" type="email" required autocomplete="email" />

                @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! auth()->user()->hasVerifiedEmail())
                    <div class="mt-2 text-xs text-amber-700 dark:text-amber-300">
                        <p>{{ __('Your email address is unverified.') }}</p>
                        <button type="button" class="underline text-brand-600 hover:text-brand-700 cursor-pointer mt-1" wire:click.prevent="resendVerificationNotification">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>

                        @if (session('status') === 'verification-link-sent')
                            <p class="mt-1 font-medium text-emerald-600 dark:text-emerald-400">
                                {{ __('A new verification link has been sent to your email address.') }}
                            </p>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Action buttons -->
            <div class="flex items-center gap-3 pt-2">
                <x-admin.button variant="primary" type="submit" icon="check">
                    {{ __('Save Changes') }}
                </x-admin.button>

                <x-action-message class="me-3" on="profile-updated">
                    {{ __('Saved successfully.') }}
                </x-action-message>
            </div>
        </form>

        <livewire:settings.delete-user-form />
    </x-settings.layout>
</section>
