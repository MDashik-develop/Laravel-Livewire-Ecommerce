<div
    x-data="{ show: @entangle('isOpen'), timer: null }"
    x-init="
        $watch('show', value => {
            if (value) {
                clearTimeout(timer);
                timer = setTimeout(() => { show = false; }, 4000);
            }
        })
    "
    x-show="show"
    x-cloak
    class="fixed inset-0 z-[99999] flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs transition-opacity"
    x-transition:enter="ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    @keydown.escape.window="show = false"
>
    {{-- Backdrop click to close --}}
    <div class="fixed inset-0" @click="show = false"></div>

    {{-- Modal Card --}}
    <div
        class="relative bg-white rounded-2xl shadow-2xl max-w-sm w-full p-6 text-center transform transition-all z-10 border border-gray-100"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
    >
        {{-- Close button (X) --}}
        <button
            type="button"
            @click="show = false"
            class="absolute top-3 right-3 text-gray-400 hover:text-gray-600 p-1.5 rounded-full hover:bg-gray-100 transition cursor-pointer"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>

        {{-- Icon --}}
        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full mb-3
            {{ ($toast['type'] ?? 'success') === 'success' ? 'bg-emerald-100 text-emerald-600' : 'bg-blue-100 text-blue-600' }}">
            @if(($toast['type'] ?? 'success') === 'success')
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            @else
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            @endif
        </div>

        {{-- Heading --}}
        <h3 class="text-lg font-bold text-gray-800">
            {{ $toast['title'] ?? 'Notice' }}
        </h3>

        {{-- Message --}}
        @if(!empty($toast['message']))
            <p class="text-sm text-gray-500 mt-1.5 leading-relaxed">
                {{ $toast['message'] }}
            </p>
        @endif

        {{-- Okay Button --}}
        <div class="mt-5 flex justify-center">
            <button
                type="button"
                @click="show = false"
                class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-2.5 px-6 rounded-xl transition duration-150 cursor-pointer shadow-sm shadow-emerald-200 text-sm"
            >
                Okay
            </button>
        </div>
    </div>
</div>
