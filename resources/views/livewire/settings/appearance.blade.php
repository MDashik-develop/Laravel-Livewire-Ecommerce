<?php

use Livewire\Volt\Component;

new class extends Component {
    //
}; ?>

<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('Appearance')" :subheading="__('Update the appearance settings for your account')">
        <div x-data="{
            theme: localStorage.theme || 'light',
            setTheme(val) {
                this.theme = val;
                if (val === 'dark') {
                    document.documentElement.classList.add('dark');
                    localStorage.theme = 'dark';
                } else if (val === 'light') {
                    document.documentElement.classList.remove('dark');
                    localStorage.theme = 'light';
                } else {
                    localStorage.removeItem('theme');
                    if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
                        document.documentElement.classList.add('dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                    }
                }
            }
        }" class="inline-flex p-1 bg-slate-100 dark:bg-zinc-800 rounded-[4px] border border-slate-200 dark:border-zinc-700">
            <button type="button" @click="setTheme('light')" 
                :class="theme === 'light' ? 'bg-white dark:bg-zinc-700 text-brand-600 dark:text-brand-400 shadow-xs' : 'text-slate-600 dark:text-zinc-400 hover:text-slate-900 dark:hover:text-white'"
                class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-[3px] transition cursor-pointer">
                <i data-lucide="sun" class="w-3.5 h-3.5"></i>
                <span>{{ __('Light') }}</span>
            </button>

            <button type="button" @click="setTheme('dark')" 
                :class="theme === 'dark' ? 'bg-white dark:bg-zinc-700 text-brand-600 dark:text-brand-400 shadow-xs' : 'text-slate-600 dark:text-zinc-400 hover:text-slate-900 dark:hover:text-white'"
                class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-[3px] transition cursor-pointer">
                <i data-lucide="moon" class="w-3.5 h-3.5"></i>
                <span>{{ __('Dark') }}</span>
            </button>

            <button type="button" @click="setTheme('system')" 
                :class="theme === 'system' ? 'bg-white dark:bg-zinc-700 text-brand-600 dark:text-brand-400 shadow-xs' : 'text-slate-600 dark:text-zinc-400 hover:text-slate-900 dark:hover:text-white'"
                class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-[3px] transition cursor-pointer">
                <i data-lucide="laptop" class="w-3.5 h-3.5"></i>
                <span>{{ __('System') }}</span>
            </button>
        </div>
    </x-settings.layout>
</section>
