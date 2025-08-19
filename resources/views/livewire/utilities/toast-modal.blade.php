<flux:modal name="toast-modal" class="md:w-96">
    <div class="space-y-6 text-center">
        @if(!empty($toast))
            <flux:heading size="lg">{{ $toast['title'] }}</flux:heading>
            <flux:text class="mt-2">{{ $toast['message'] }}</flux:text>
        @endif
        <div class="flex justify-center">
            <flux:modal.close>
                <flux:button variant="primary">Okay</flux:button>
            </flux:modal.close>
        </div>
    </div>
</flux:modal>
