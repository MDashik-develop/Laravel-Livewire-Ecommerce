<?php

namespace App\Livewire\Backend\Stores;

use Flux\Flux;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Store;
use Illuminate\Support\Facades\Storage;

class Approval extends Component
{
    use WithPagination;

    public string $search = '';
    public ?int $storeId = null;

    /**
     * Confirm approve modal
     */
    public function confirmApprove(int $id): void
    {
        $this->storeId = $id;
    }

    /**
     * Approve the store
     */
    public function approve(): void
    {
        $store = Store::findOrFail($this->storeId);
        $store->update(['is_approved' => true]);

        $this->storeId = null;
        Flux::modal('approve-modal')->close();

        $this->dispatch('show-toast', [
            'title'   => 'Approved 🎉',
            'message' => 'The store has been approved successfully.',
            'type'    => 'success',
        ]);
    }

    /**
     * Confirm delete modal
     */
    public function confirmDelete(int $id): void
    {
        $this->storeId = $id;
    }

    /**
     * Delete the store
     */
    public function delete(): void
    {
        $store = Store::findOrFail($this->storeId);

        // Delete logo if exists
        if ($store->logo_path && Storage::disk('public')->exists($store->logo_path)) {
            Storage::disk('public')->delete($store->logo_path);
        }

        $store->delete();

        $this->storeId = null;
        Flux::modal('delete-modal')->close();

        $this->dispatch('show-toast', [
            'title'   => 'Deleted 🗑️',
            'message' => 'The store has been deleted successfully.',
            'type'    => 'success',
        ]);
    }

    /**
     * Render table
     */
    public function render()
    {
        $stores = Store::with('user')
            ->where('name', 'like', '%' . $this->search . '%')
            ->where('is_approved', false)
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('livewire.backend.stores.approval', [
            'stores' => $stores,
        ]);
    }
}
