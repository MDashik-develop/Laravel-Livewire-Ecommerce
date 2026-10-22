<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;

new class extends Component {
    public string $password = '';

    /**
     * Delete the currently authenticated user.
     */
    public function deleteUser(Logout $logout): void
    {
        $this->validate([
            'password' => ['required', 'string', 'current_password'],
        ]);

        tap(Auth::user(), $logout(...))->delete();

        $this->redirect('/', navigate: true);
    }
}; ?>

<section class="mt-8 pt-6 border-t border-slate-200 dark:border-zinc-800 space-y-4">
    <div class="relative">
        <h3 class="text-sm font-bold text-rose-600 dark:text-rose-400 uppercase tracking-wider">{{ __('Delete account') }}</h3>
        <p class="text-xs text-slate-500 dark:text-zinc-400 mt-0.5">{{ __('Permanently delete your account and all of its resources') }}</p>
    </div>

    <x-admin.button variant="danger" type="button" @click="$dispatch('modal-show', { name: 'confirm-user-deletion' })">
        {{ __('Delete account') }}
    </x-admin.button>

    <x-admin.modal name="confirm-user-deletion" title="{{ __('Delete account') }}" maxWidth="max-w-lg">
        <form method="POST" wire:submit="deleteUser" class="space-y-4">
            <div class="space-y-1">
                <h4 class="text-sm font-bold text-slate-800 dark:text-zinc-100">{{ __('Are you sure you want to delete your account?') }}</h4>
                <p class="text-xs text-slate-500 dark:text-zinc-400">
                    {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
                </p>
            </div>

            <x-admin.input wire:model="password" :label="__('Password')" type="password" placeholder="Enter password to confirm" required />

            <div class="flex justify-end gap-2 pt-2 border-t border-slate-100 dark:border-zinc-800">
                <x-admin.button type="button" variant="ghost" @click="$dispatch('modal-close', { name: 'confirm-user-deletion' })">
                    {{ __('Cancel') }}
                </x-admin.button>

                <x-admin.button variant="danger" type="submit">
                    {{ __('Delete account') }}
                </x-admin.button>
            </div>
        </form>
    </x-admin.modal>
</section>
