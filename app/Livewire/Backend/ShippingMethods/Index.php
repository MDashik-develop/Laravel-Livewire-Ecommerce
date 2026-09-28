<?php

namespace App\Livewire\Backend\ShippingMethods;

use App\Models\ShippingMethod;
use Flux\Flux;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    // Search and Sort
    public string $search = '';
    public string $sortField = 'id';
    public string $sortDirection = 'desc';

    // Form fields
    public ?int $shippingMethodId = null;
    public string $name = '';
    public string $code = '';
    public string|float $base_charge = '0.00';
    public string|float $per_kg_charge = '0.00';
    public bool $status = true;
    public bool $cod_available = false;
    public ?string $description = '';

    protected function rules(): array
    {
        return [
            'name' => 'required|string|min:2|max:255',
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('shipping_methods', 'code')->ignore($this->shippingMethodId),
            ],
            'base_charge' => 'required|numeric|min:0',
            'per_kg_charge' => 'required|numeric|min:0',
            'status' => 'required|boolean',
            'cod_available' => 'required|boolean',
            'description' => 'nullable|string|max:1000',
        ];
    }

    public function updatedName(string $value): void
    {
        if (! $this->shippingMethodId && empty($this->code)) {
            $this->code = Str::slug($value, '_');
        }
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function resetForm(): void
    {
        $this->reset([
            'shippingMethodId',
            'name',
            'code',
            'base_charge',
            'per_kg_charge',
            'status',
            'cod_available',
            'description',
        ]);
        $this->base_charge = '0.00';
        $this->per_kg_charge = '0.00';
        $this->status = true;
        $this->cod_available = false;
        $this->resetValidation();
    }

    public function create(): void
    {
        $this->resetForm();
        Flux::modal('shipping-method-modal')->show();
    }

    public function edit(int $id): void
    {
        $this->resetValidation();
        $method = ShippingMethod::findOrFail($id);

        $this->shippingMethodId = $method->id;
        $this->name = $method->name;
        $this->code = $method->code;
        $this->base_charge = (string) $method->base_charge;
        $this->per_kg_charge = (string) $method->per_kg_charge;
        $this->status = (bool) $method->status;
        $this->cod_available = (bool) $method->cod_available;
        $this->description = $method->description;

        Flux::modal('shipping-method-modal')->show();
    }

    public function save(): void
    {
        $validatedData = $this->validate();

        DB::beginTransaction();
        try {
            if ($this->shippingMethodId) {
                $method = ShippingMethod::findOrFail($this->shippingMethodId);
                $method->update($validatedData);
                $message = 'Shipping method updated successfully!';
            } else {
                ShippingMethod::create($validatedData);
                $message = 'Shipping method created successfully!';
            }

            DB::commit();

            Flux::modal('shipping-method-modal')->close();
            $this->resetForm();

            $this->dispatch('show-toast', [
                'title' => 'Success 🎉',
                'message' => $message,
                'type' => 'success',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            Log::error('Failed to save shipping method: ' . $e->getMessage(), [
                'exception' => $e,
            ]);

            $this->dispatch('show-toast', [
                'title' => 'Error ❌',
                'message' => 'Failed to save shipping method: ' . $e->getMessage(),
                'type' => 'error',
            ]);
        }
    }

    public function confirmDelete(int $id): void
    {
        $this->shippingMethodId = $id;
        Flux::modal('delete-shipping-modal')->show();
    }

    public function delete(): void
    {
        if (! $this->shippingMethodId) {
            return;
        }

        DB::beginTransaction();
        try {
            $method = ShippingMethod::findOrFail($this->shippingMethodId);
            $method->delete();

            DB::commit();

            Flux::modal('delete-shipping-modal')->close();
            $this->resetForm();

            $this->dispatch('show-toast', [
                'title' => 'Deleted 🗑️',
                'message' => 'Shipping method deleted successfully!',
                'type' => 'success',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            Log::error('Failed to delete shipping method: ' . $e->getMessage(), [
                'exception' => $e,
            ]);

            $this->dispatch('show-toast', [
                'title' => 'Error ❌',
                'message' => 'Failed to delete shipping method: ' . $e->getMessage(),
                'type' => 'error',
            ]);
        }
    }

    public function toggleStatus(int $id): void
    {
        DB::beginTransaction();
        try {
            $method = ShippingMethod::findOrFail($id);
            $method->status = ! $method->status;
            $method->save();

            DB::commit();

            $this->dispatch('show-toast', [
                'title' => 'Updated ⚡',
                'message' => 'Shipping method status updated.',
                'type' => 'success',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            Log::error('Failed to toggle shipping method status: ' . $e->getMessage(), [
                'exception' => $e,
            ]);

            $this->dispatch('show-toast', [
                'title' => 'Error ❌',
                'message' => 'Failed to update status: ' . $e->getMessage(),
                'type' => 'error',
            ]);
        }
    }

    public function toggleCod(int $id): void
    {
        DB::beginTransaction();
        try {
            $method = ShippingMethod::findOrFail($id);
            $method->cod_available = ! $method->cod_available;
            $method->save();

            DB::commit();

            $this->dispatch('show-toast', [
                'title' => 'Updated ⚡',
                'message' => 'COD availability updated.',
                'type' => 'success',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            Log::error('Failed to toggle shipping method COD: ' . $e->getMessage(), [
                'exception' => $e,
            ]);

            $this->dispatch('show-toast', [
                'title' => 'Error ❌',
                'message' => 'Failed to update COD availability: ' . $e->getMessage(),
                'type' => 'error',
            ]);
        }
    }

    public function render()
    {
        $methods = ShippingMethod::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('code', 'like', '%' . $this->search . '%')
                        ->orWhere('description', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.backend.shipping-methods.index', [
            'methods' => $methods,
        ]);
    }
}
