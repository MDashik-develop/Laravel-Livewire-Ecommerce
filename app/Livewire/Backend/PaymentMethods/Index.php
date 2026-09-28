<?php

namespace App\Livewire\Backend\PaymentMethods;

use App\Models\Media;
use App\Models\PaymentMethod;
use Flux\Flux;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;
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
    public ?int $paymentMethodId = null;
    public string $name = '';
    public string $slug = '';
    public bool $is_active = true;
    public bool $sandbox_mode = false;
    public ?string $instruction = '';
    public ?int $media_id = null;
    public ?string $mediaUrl = null;

    // Dynamic Credentials Key-Value pairs
    public array $credentialRows = [
        ['key' => '', 'value' => ''],
    ];

    protected function rules(): array
    {
        return [
            'name' => 'required|string|min:2|max:255',
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('payment_methods', 'slug')->ignore($this->paymentMethodId),
            ],
            'is_active' => 'required|boolean',
            'sandbox_mode' => 'required|boolean',
            'instruction' => 'nullable|string|max:2000',
            'media_id' => 'nullable|exists:media,id',
            'credentialRows.*.key' => 'nullable|string|max:100',
            'credentialRows.*.value' => 'nullable|string|max:1000',
        ];
    }

    #[On('payment-media-selected')]
    public function handleMediaSelected($media): void
    {
        if (is_array($media)) {
            $this->media_id = $media['id'] ?? null;
            $this->mediaUrl = $media['urls']['small'] ?? $media['url'] ?? null;
        } elseif (is_numeric($media)) {
            $this->media_id = (int) $media;
            $m = Media::find($media);
            $this->mediaUrl = $m?->urls['small'] ?? $m?->url;
        }
    }

    public function removeMedia(): void
    {
        $this->media_id = null;
        $this->mediaUrl = null;
    }

    public function updatedName(string $value): void
    {
        if (! $this->paymentMethodId || empty($this->slug)) {
            $this->slug = Str::slug($value);
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

    public function addCredentialRow(): void
    {
        $this->credentialRows[] = ['key' => '', 'value' => ''];
    }

    public function removeCredentialRow(int $index): void
    {
        unset($this->credentialRows[$index]);
        $this->credentialRows = array_values($this->credentialRows);

        if (empty($this->credentialRows)) {
            $this->credentialRows = [['key' => '', 'value' => '']];
        }
    }

    public function resetForm(): void
    {
        $this->reset([
            'paymentMethodId',
            'name',
            'slug',
            'is_active',
            'sandbox_mode',
            'instruction',
            'media_id',
            'mediaUrl',
        ]);
        $this->is_active = true;
        $this->sandbox_mode = false;
        $this->credentialRows = [['key' => '', 'value' => '']];
        $this->resetValidation();
    }

    public function create(): void
    {
        $this->resetForm();
        Flux::modal('payment-method-modal')->show();
    }

    public function edit(int $id): void
    {
        $this->resetValidation();
        $method = PaymentMethod::with('media')->findOrFail($id);

        $this->paymentMethodId = $method->id;
        $this->name = $method->name;
        $this->slug = $method->slug;
        $this->is_active = (bool) $method->is_active;
        $this->sandbox_mode = (bool) $method->sandbox_mode;
        $this->instruction = $method->instruction;
        $this->media_id = $method->media_id;
        $this->mediaUrl = $method->media?->urls['small'] ?? $method->media?->url;

        $rows = [];
        if (is_array($method->credentials) && count($method->credentials) > 0) {
            foreach ($method->credentials as $key => $val) {
                $rows[] = [
                    'key' => (string) $key,
                    'value' => is_scalar($val) ? (string) $val : json_encode($val),
                ];
            }
        }
        $this->credentialRows = ! empty($rows) ? $rows : [['key' => '', 'value' => '']];

        Flux::modal('payment-method-modal')->show();
    }

    public function save(): void
    {
        $this->validate();

        // Process credentials array from rows
        $credentials = [];
        foreach ($this->credentialRows as $row) {
            $k = trim($row['key'] ?? '');
            $v = trim($row['value'] ?? '');
            if ($k !== '') {
                $credentials[$k] = $v;
            }
        }

        $data = [
            'name' => $this->name,
            'slug' => $this->slug,
            'is_active' => $this->is_active,
            'sandbox_mode' => $this->sandbox_mode,
            'instruction' => $this->instruction,
            'media_id' => $this->media_id,
            'credentials' => ! empty($credentials) ? $credentials : null,
        ];

        DB::beginTransaction();
        try {
            if ($this->paymentMethodId) {
                $method = PaymentMethod::findOrFail($this->paymentMethodId);
                $method->update($data);
                $message = 'Payment method updated successfully!';
            } else {
                PaymentMethod::create($data);
                $message = 'Payment method created successfully!';
            }

            DB::commit();

            Flux::modal('payment-method-modal')->close();
            $this->resetForm();

            $this->dispatch('show-toast', [
                'title' => 'Success 🎉',
                'message' => $message,
                'type' => 'success',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            Log::error('Failed to save payment method: ' . $e->getMessage(), [
                'exception' => $e,
            ]);

            $this->dispatch('show-toast', [
                'title' => 'Error ❌',
                'message' => 'Failed to save payment method: ' . $e->getMessage(),
                'type' => 'error',
            ]);
        }
    }

    public function confirmDelete(int $id): void
    {
        $this->paymentMethodId = $id;
        Flux::modal('delete-payment-modal')->show();
    }

    public function delete(): void
    {
        if (! $this->paymentMethodId) {
            return;
        }

        DB::beginTransaction();
        try {
            $method = PaymentMethod::findOrFail($this->paymentMethodId);
            $method->delete();

            DB::commit();

            Flux::modal('delete-payment-modal')->close();
            $this->resetForm();

            $this->dispatch('show-toast', [
                'title' => 'Deleted 🗑️',
                'message' => 'Payment method deleted successfully!',
                'type' => 'success',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            Log::error('Failed to delete payment method: ' . $e->getMessage(), [
                'exception' => $e,
            ]);

            $this->dispatch('show-toast', [
                'title' => 'Error ❌',
                'message' => 'Failed to delete payment method: ' . $e->getMessage(),
                'type' => 'error',
            ]);
        }
    }

    public function toggleActive(int $id): void
    {
        DB::beginTransaction();
        try {
            $method = PaymentMethod::findOrFail($id);
            $method->is_active = ! $method->is_active;
            $method->save();

            DB::commit();

            $this->dispatch('show-toast', [
                'title' => 'Updated ⚡',
                'message' => 'Payment method status updated.',
                'type' => 'success',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            Log::error('Failed to toggle payment method active status: ' . $e->getMessage(), [
                'exception' => $e,
            ]);

            $this->dispatch('show-toast', [
                'title' => 'Error ❌',
                'message' => 'Failed to update status: ' . $e->getMessage(),
                'type' => 'error',
            ]);
        }
    }

    public function toggleSandbox(int $id): void
    {
        DB::beginTransaction();
        try {
            $method = PaymentMethod::findOrFail($id);
            $method->sandbox_mode = ! $method->sandbox_mode;
            $method->save();

            DB::commit();

            $this->dispatch('show-toast', [
                'title' => 'Updated ⚡',
                'message' => 'Sandbox mode updated.',
                'type' => 'success',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            Log::error('Failed to toggle payment method sandbox mode: ' . $e->getMessage(), [
                'exception' => $e,
            ]);

            $this->dispatch('show-toast', [
                'title' => 'Error ❌',
                'message' => 'Failed to update sandbox mode: ' . $e->getMessage(),
                'type' => 'error',
            ]);
        }
    }

    public function render()
    {
        $methods = PaymentMethod::with('media')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('slug', 'like', '%' . $this->search . '%')
                        ->orWhere('instruction', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.backend.payment-methods.index', [
            'methods' => $methods,
        ]);
    }
}
