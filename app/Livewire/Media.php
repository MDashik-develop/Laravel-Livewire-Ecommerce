<?php

namespace App\Livewire;

use App\Models\Media as MediaModel;
use App\Services\MediaService;
use Flux\Flux;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Media extends Component
{
    use WithPagination, WithFileUploads;

    // Active tab: 'all', 'folders', 'upload', 'trash'
    public string $tab = 'all';

    // Modal state
    public bool $isOpen = false;
    public ?string $targetEvent = null;
    public bool $isPicker = false;
    public ?int $selectedMediaId = null;

    // Filter & Search
    public string $search = '';
    public string $typeFilter = 'all'; // 'all', 'image', 'video', 'gif'
    public ?string $currentFolder = null;

    // Trash state
    public ?string $trashFolder = null;
    public ?int $selectedTrashId = null;

    // Upload properties
    public $uploads = [];
    public array $uploadTitles = [];
    public string $uploadFolder = 'default';
    public string $newFolderName = '';
    public bool $isCreatingFolder = false;

    // Feedback
    public ?string $notification = null;
    public ?string $notificationType = 'success';

    protected function rules(): array
    {
        return [
            'uploads.*' => 'required|file|max:204800', // 200MB max per file
            'uploadTitles.*' => 'nullable|string|max:255',
        ];
    }

    protected $messages = [
        'uploads.*.max' => 'Each file may not be greater than 200MB.',
    ];

    #[On('open-media-modal')]
    public function openModal(?string $targetEvent = null, ?string $folder = null, ?string $type = null, bool $isPicker = true): void
    {
        $this->isOpen = true;
        $this->targetEvent = $targetEvent;
        $this->isPicker = $isPicker;
        $this->selectedMediaId = null;
        $this->currentFolder = $folder;
        if ($folder) {
            $this->uploadFolder = $folder;
        }
        $this->typeFilter = $type ?: 'all';
        $this->tab = 'all';
        $this->search = '';

        try {
            Flux::modal('media-manager-modal')->show();
        } catch (\Throwable $e) {
            // fallback if flux modal helper isn't directly bound
        }
    }

    #[On('close-media-modal')]
    public function closeModal(): void
    {
        $this->isOpen = false;
        $this->selectedMediaId = null;

        try {
            Flux::modal('media-manager-modal')->close();
        } catch (\Throwable $e) {
            // fallback
        }
    }

    public function setTab(string $tab): void
    {
        $this->tab = $tab;
        $this->resetPage();
    }

    public function setTypeFilter(string $type): void
    {
        $this->typeFilter = $type;
        $this->resetPage();
    }

    public function openFolder(string $folder): void
    {
        $this->currentFolder = $folder;
        $this->uploadFolder = $folder;
        $this->tab = 'all';
        $this->resetPage();
    }

    public function clearFolder(): void
    {
        $this->currentFolder = null;
        $this->resetPage();
    }

    public function selectMedia(int $id): void
    {
        if ($this->selectedMediaId === $id) {
            $this->selectedMediaId = null;
        } else {
            $this->selectedMediaId = $id;
        }
    }

    public function confirmSelection(): void
    {
        if (!$this->selectedMediaId) {
            return;
        }

        $media = MediaModel::find($this->selectedMediaId);
        if (!$media) {
            return;
        }

        if ($this->targetEvent) {
            $this->dispatch($this->targetEvent, mediaId: $media->id, url: $media->url, media: [
                'id'    => $media->id,
                'title' => $media->title,
                'url'   => $media->url,
                'urls'  => $media->urls,
                'type'  => $media->type,
            ]);
        }

        $this->dispatch('media-selected', mediaId: $media->id, url: $media->url, media: [
            'id'    => $media->id,
            'title' => $media->title,
            'url'   => $media->url,
            'urls'  => $media->urls,
            'type'  => $media->type,
        ]);

        $this->closeModal();
    }

    public function updatedUploads(): void
    {
        foreach ($this->uploads as $index => $file) {
            if (!isset($this->uploadTitles[$index]) || empty($this->uploadTitles[$index])) {
                $this->uploadTitles[$index] = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            }
        }
    }

    public function saveUploads(MediaService $service): void
    {
        $this->validate();

        $targetFolder = trim($this->uploadFolder) ?: 'default';
        $count = 0;

        foreach ($this->uploads as $index => $file) {
            $customTitle = !empty($this->uploadTitles[$index]) ? trim($this->uploadTitles[$index]) : null;
            $service->upload($file, $targetFolder, $customTitle);
            $count++;
        }

        $this->uploads = [];
        $this->uploadTitles = [];
        $this->tab = 'all';
        $this->currentFolder = $targetFolder;
        $this->resetPage();

        $this->showNotification("{$count} file(s) uploaded successfully!", 'success');
    }

    public function createFolder(): void
    {
        $folder = Str::slug(trim($this->newFolderName));
        if (empty($folder)) {
            $this->showNotification('Please enter a valid folder name.', 'error');
            return;
        }

        $this->uploadFolder = $folder;
        $this->currentFolder = $folder;
        $this->newFolderName = '';
        $this->isCreatingFolder = false;
        $this->tab = 'all';
        $this->resetPage();

        $this->showNotification("Folder '{$folder}' ready.", 'success');
    }

    public function removeUpload(int $index): void
    {
        unset($this->uploads[$index]);
        $this->uploads = array_values($this->uploads);

        unset($this->uploadTitles[$index]);
        $this->uploadTitles = array_values($this->uploadTitles);
    }

    public function deleteMedia(int $id, MediaService $service): void
    {
        $service->delete($id, force: false); // Soft delete

        if ($this->selectedMediaId === $id) {
            $this->selectedMediaId = null;
        }

        $this->showNotification('Media moved to Trash.', 'success');
    }

    public function setTrashFolder(?string $folder): void
    {
        $this->trashFolder = $folder;
        $this->selectedTrashId = null;
        $this->resetPage();
    }

    public function selectTrashMedia(int $id): void
    {
        $this->selectedTrashId = ($this->selectedTrashId === $id) ? null : $id;
    }

    public function restoreMedia(int $id, MediaService $service): void
    {
        $service->restore($id);

        if ($this->selectedTrashId === $id) {
            $this->selectedTrashId = null;
        }

        $this->showNotification('Media restored successfully!', 'success');
    }

    public function forceDeleteMedia(int $id, MediaService $service): void
    {
        $service->delete($id, force: true);

        if ($this->selectedTrashId === $id) {
            $this->selectedTrashId = null;
        }

        $this->showNotification('Media permanently deleted.', 'success');
    }

    public function emptyTrash(MediaService $service): void
    {
        $count = $service->emptyTrash($this->trashFolder);
        $this->selectedTrashId = null;
        $this->showNotification("{$count} media item(s) permanently deleted.", 'success');
    }

    public function restoreAllTrash(MediaService $service): void
    {
        $count = $service->restoreAllTrash($this->trashFolder);
        $this->selectedTrashId = null;
        $this->showNotification("{$count} media item(s) restored.", 'success');
    }

    public function showNotification(string $message, string $type = 'success'): void
    {
        $this->notification = $message;
        $this->notificationType = $type;

        $this->dispatch('show-toast', [
            'title'   => $type === 'success' ? 'Success 🎉' : 'Alert ⚠️',
            'message' => $message,
            'type'    => $type,
        ]);
    }

    public function render(MediaService $service)
    {
        // Query active media
        $query = MediaModel::query()->latest();

        if ($this->currentFolder) {
            $query->where('folder', $this->currentFolder);
        }

        if ($this->typeFilter && $this->typeFilter !== 'all') {
            $query->where('type', $this->typeFilter);
        }

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('path', 'like', '%' . $this->search . '%');
            });
        }

        $mediaItems = $query->paginate(24);
        $folders = $service->getFolders();
        $selectedMedia = $this->selectedMediaId ? MediaModel::find($this->selectedMediaId) : null;

        // Trash data
        $trashedCount = MediaModel::onlyTrashed()->count();
        $trashedFolders = $service->getTrashedFolders();
        $trashedItems = collect();

        if ($this->tab === 'trash') {
            $trashQuery = MediaModel::onlyTrashed()->latest('deleted_at');

            if ($this->trashFolder) {
                $trashQuery->where('folder', $this->trashFolder);
            }

            if (!empty($this->search)) {
                $trashQuery->where(function ($q) {
                    $q->where('title', 'like', '%' . $this->search . '%')
                      ->orWhere('path', 'like', '%' . $this->search . '%');
                });
            }

            $trashedItems = $trashQuery->paginate(24);
        }

        $selectedTrashMedia = $this->selectedTrashId ? MediaModel::onlyTrashed()->find($this->selectedTrashId) : null;

        return view('livewire.media', [
            'mediaItems'         => $mediaItems,
            'folders'            => $folders,
            'selectedMedia'      => $selectedMedia,
            'trashedCount'       => $trashedCount,
            'trashedFolders'     => $trashedFolders,
            'trashedItems'       => $trashedItems,
            'selectedTrashMedia' => $selectedTrashMedia,
        ]);
    }
}
