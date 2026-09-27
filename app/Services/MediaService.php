<?php

namespace App\Services;

use App\Models\Media;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MediaService
{
    protected ?object $imageManager = null;

    public function __construct()
    {
        if (class_exists(\Intervention\Image\ImageManager::class) && class_exists(\Intervention\Image\Drivers\Gd\Driver::class)) {
            try {
                $driverClass = \Intervention\Image\Drivers\Gd\Driver::class;
                $managerClass = \Intervention\Image\ImageManager::class;
                $this->imageManager = new $managerClass(new $driverClass());
            } catch (\Throwable $e) {
                $this->imageManager = null;
            }
        }
    }

    /**
     * Upload and process a media file.
     */
    public function upload(UploadedFile $file, string $folder = 'default', ?string $customTitle = null): Media
    {
        $folder = trim($folder) ?: 'default';
        $originalName = $file->getClientOriginalName();
        $rawExtension = strtolower($file->getClientOriginalExtension());
        $mimeType = $file->getMimeType() ?: 'application/octet-stream';
        $fileSize = $file->getSize(); // in bytes

        $title = $customTitle ?: pathinfo($originalName, PATHINFO_FILENAME);
        $slug = Str::slug($title) ?: 'media';
        $uniqueSuffix = substr(uniqid(), -4);
        $baseName = $slug . '_' . $uniqueSuffix;

        $type = $this->determineMediaType($rawExtension, $mimeType);
        $sizes = [];
        $savedExtension = $rawExtension;

        // Ensure directories exist
        $this->ensureDirectories();

        if ($type === 'gif') {
            $fileName = $baseName . '.gif';
            $savedExtension = 'gif';
            $destination = storage_path('app/public/media/gif/' . $fileName);
            $file->move(storage_path('app/public/media/gif'), $fileName);

            $formattedSize = round($fileSize / 1024, 2) . ' KB';
            $sizes = [
                'original' => $formattedSize,
                'gif'      => $formattedSize,
            ];
        } elseif ($type === 'video') {
            $fileName = $baseName . '.' . $rawExtension;
            $destination = storage_path('app/public/media/videos/' . $fileName);
            $file->move(storage_path('app/public/media/videos'), $fileName);

            $formattedSize = round($fileSize / 1024, 2) . ' KB';
            $sizes = [
                'original' => $formattedSize,
                'video'    => $formattedSize,
            ];
        } elseif ($rawExtension === 'svg') {
            $fileName = $baseName . '.svg';
            $savedExtension = 'svg';
            $destination = storage_path('app/public/media/original/' . $fileName);
            $file->move(storage_path('app/public/media/original'), $fileName);

            // Copy to other folders for fallback
            File::copy($destination, storage_path('app/public/media/large/' . $fileName));
            File::copy($destination, storage_path('app/public/media/small/' . $fileName));
            File::copy($destination, storage_path('app/public/media/thumb/' . $fileName));

            $formattedSize = round($fileSize / 1024, 2) . ' KB';
            $sizes = [
                'original' => $formattedSize,
                'large'    => $formattedSize,
                'small'    => $formattedSize,
                'thumb'    => $formattedSize,
            ];
        } else {
            // Standard Image (JPG, PNG, WEBP, BMP, etc.) -> Process into WebP variants
            $fileName = $baseName . '.webp';
            $savedExtension = 'webp';
            $sourcePath = $file->getRealPath();

            $origPath = storage_path('app/public/media/original/' . $fileName);
            $largePath = storage_path('app/public/media/large/' . $fileName);
            $smallPath = storage_path('app/public/media/small/' . $fileName);
            $thumbPath = storage_path('app/public/media/thumb/' . $fileName);

            if ($this->imageManager) {
                // 1. Original
                $origImage = $this->readImage($sourcePath);
                $this->saveAsWebp($origImage, $origPath, 90);

                // 2. Large (max width 1000px)
                $largeImage = $this->readImage($sourcePath);
                if ($largeImage && $largeImage->width() > 1000) {
                    if (method_exists($largeImage, 'scaleDown')) {
                        $largeImage->scaleDown(width: 1000);
                    } elseif (method_exists($largeImage, 'scale')) {
                        $largeImage->scale(width: 1000);
                    }
                }
                $this->saveAsWebp($largeImage, $largePath, 90);

                // 3. Small (max width 400px)
                $smallImage = $this->readImage($sourcePath);
                if ($smallImage && $smallImage->width() > 400) {
                    if (method_exists($smallImage, 'scaleDown')) {
                        $smallImage->scaleDown(width: 400);
                    } elseif (method_exists($smallImage, 'scale')) {
                        $smallImage->scale(width: 400);
                    }
                }
                $this->saveAsWebp($smallImage, $smallPath, 90);

                // 4. Thumb (150x150 square crop)
                $thumbImage = $this->readImage($sourcePath);
                if ($thumbImage && method_exists($thumbImage, 'cover')) {
                    $thumbImage->cover(150, 150);
                }
                $this->saveAsWebp($thumbImage, $thumbPath, 90);
            } else {
                // Pure Native GD Fallback Engine
                $this->processWithNativeGd($sourcePath, $origPath, $largePath, $smallPath, $thumbPath);
            }

            $sizes['original'] = File::exists($origPath) ? round(File::size($origPath) / 1024, 2) . ' KB' : round($fileSize / 1024, 2) . ' KB';
            $sizes['large'] = File::exists($largePath) ? round(File::size($largePath) / 1024, 2) . ' KB' : $sizes['original'];
            $sizes['small'] = File::exists($smallPath) ? round(File::size($smallPath) / 1024, 2) . ' KB' : $sizes['original'];
            $sizes['thumb'] = File::exists($thumbPath) ? round(File::size($thumbPath) / 1024, 2) . ' KB' : $sizes['original'];
        }

        return Media::create([
            'user_id'   => Auth::id(),
            'title'     => $title,
            'path'      => $fileName,
            'folder'    => $folder,
            'type'      => $type,
            'extension' => $savedExtension,
            'mime_type' => $mimeType,
            'size'      => $fileSize,
            'sizes'     => $sizes,
        ]);
    }

    /**
     * Native GD image processor fallback.
     */
    protected function processWithNativeGd(string $sourcePath, string $origPath, string $largePath, string $smallPath, string $thumbPath): void
    {
        $info = @getimagesize($sourcePath);
        if (!$info) {
            @copy($sourcePath, $origPath);
            @copy($sourcePath, $largePath);
            @copy($sourcePath, $smallPath);
            @copy($sourcePath, $thumbPath);
            return;
        }

        $width = $info[0];
        $height = $info[1];
        $mime = $info['mime'];

        $srcImg = match ($mime) {
            'image/jpeg' => @imagecreatefromjpeg($sourcePath),
            'image/png'  => @imagecreatefrompng($sourcePath),
            'image/webp' => @imagecreatefromwebp($sourcePath),
            'image/gif'  => @imagecreatefromgif($sourcePath),
            default      => null,
        };

        if (!$srcImg) {
            @copy($sourcePath, $origPath);
            @copy($sourcePath, $largePath);
            @copy($sourcePath, $smallPath);
            @copy($sourcePath, $thumbPath);
            return;
        }

        // 1. Original (WebP format)
        if (function_exists('imagewebp')) {
            imagewebp($srcImg, $origPath, 90);
        } else {
            imagejpeg($srcImg, $origPath, 90);
        }

        // 2. Large (Scale width to max 1000px)
        $this->resizeNativeGd($srcImg, $width, $height, 1000, null, $largePath);

        // 3. Small (Scale width to max 400px)
        $this->resizeNativeGd($srcImg, $width, $height, 400, null, $smallPath);

        // 4. Thumb (Square 150x150 crop)
        $this->cropSquareNativeGd($srcImg, $width, $height, 150, $thumbPath);

        imagedestroy($srcImg);
    }

    protected function resizeNativeGd($srcImg, int $srcW, int $srcH, ?int $maxW, ?int $maxH, string $destination): void
    {
        $targetW = $srcW;
        $targetH = $srcH;

        if ($maxW && $srcW > $maxW) {
            $targetW = $maxW;
            $targetH = (int) round(($srcH * $maxW) / $srcW);
        }

        $dstImg = imagecreatetruecolor($targetW, $targetH);
        imagealphablending($dstImg, false);
        imagesavealpha($dstImg, true);
        imagecopyresampled($dstImg, $srcImg, 0, 0, 0, 0, $targetW, $targetH, $srcW, $srcH);

        if (function_exists('imagewebp')) {
            imagewebp($dstImg, $destination, 90);
        } else {
            imagejpeg($dstImg, $destination, 90);
        }

        imagedestroy($dstImg);
    }

    protected function cropSquareNativeGd($srcImg, int $srcW, int $srcH, int $size, string $destination): void
    {
        $minDim = min($srcW, $srcH);
        $srcX = (int) round(($srcW - $minDim) / 2);
        $srcY = (int) round(($srcH - $minDim) / 2);

        $dstImg = imagecreatetruecolor($size, $size);
        imagealphablending($dstImg, false);
        imagesavealpha($dstImg, true);
        imagecopyresampled($dstImg, $srcImg, 0, 0, $srcX, $srcY, $size, $size, $minDim, $minDim);

        if (function_exists('imagewebp')) {
            imagewebp($dstImg, $destination, 90);
        } else {
            imagejpeg($dstImg, $destination, 90);
        }

        imagedestroy($dstImg);
    }

    /**
     * Read image safely using available Intervention Image methods.
     */
    protected function readImage(string $path)
    {
        if (!$this->imageManager) {
            return null;
        }

        if (method_exists($this->imageManager, 'read')) {
            return $this->imageManager->read($path);
        }
        if (method_exists($this->imageManager, 'decode')) {
            return $this->imageManager->decode($path);
        }
        if (method_exists($this->imageManager, 'make')) {
            return $this->imageManager->make($path);
        }
        return null;
    }

    /**
     * Save image as WebP format with quality.
     */
    protected function saveAsWebp($image, string $destination, int $quality = 90): void
    {
        if (!$image) {
            return;
        }

        if (method_exists($image, 'encodeUsingFileExtension')) {
            $image->encodeUsingFileExtension('webp', $quality)->save($destination);
        } elseif (method_exists($image, 'toWebp')) {
            $image->toWebp($quality)->save($destination);
        } elseif (method_exists($image, 'encode')) {
            $image->encode('webp', $quality)->save($destination);
        } else {
            $image->save($destination, $quality);
        }
    }

    /**
     * Delete media record (soft delete by default, force delete if $force is true).
     */
    public function delete(int|Media $media, bool $force = false): bool
    {
        if (is_numeric($media)) {
            $media = Media::withTrashed()->findOrFail($media);
        }

        if ($force) {
            return (bool) $media->forceDelete();
        }

        return (bool) $media->delete();
    }

    /**
     * Restore soft-deleted media by manually setting deleted_at to null for maximum performance.
     */
    public function restore(int|Media $media): bool
    {
        $id = is_numeric($media) ? $media : $media->id;
        return (bool) Media::onlyTrashed()->where('id', $id)->update(['deleted_at' => null]);
    }

    /**
     * Get distinct folders that have soft-deleted media with counts.
     */
    public function getTrashedFolders(): array
    {
        $folders = Media::onlyTrashed()
            ->selectRaw('folder, count(*) as count')
            ->groupBy('folder')
            ->orderBy('folder', 'asc')
            ->get();

        $result = [];
        foreach ($folders as $f) {
            $result[] = [
                'name'  => $f->folder,
                'count' => (int) $f->count,
            ];
        }

        return $result;
    }

    /**
     * Restore all trashed media (or optionally in a specific folder) by setting deleted_at to null.
     */
    public function restoreAllTrash(?string $folder = null): int
    {
        $query = Media::onlyTrashed();
        if ($folder) {
            $query->where('folder', $folder);
        }
        return $query->update(['deleted_at' => null]);
    }

    /**
     * Permanently delete all trashed media (or optionally in a specific folder) along with files.
     */
    public function emptyTrash(?string $folder = null): int
    {
        $query = Media::onlyTrashed();
        if ($folder) {
            $query->where('folder', $folder);
        }
        $items = $query->get();
        $count = 0;
        foreach ($items as $item) {
            $item->forceDelete();
            $count++;
        }
        return $count;
    }

    /**
     * Get distinct folders list with media counts.
     */
    public function getFolders(): array
    {
        $folders = Media::selectRaw('folder, count(*) as count')
            ->groupBy('folder')
            ->orderBy('folder', 'asc')
            ->get();

        $defaultFolders = ['default', 'products', 'categories', 'brands', 'banners'];
        $folderMap = [];

        foreach ($defaultFolders as $df) {
            $folderMap[$df] = 0;
        }

        foreach ($folders as $f) {
            $folderMap[$f->folder] = (int) $f->count;
        }

        $result = [];
        foreach ($folderMap as $name => $count) {
            $result[] = [
                'name'  => $name,
                'count' => $count,
            ];
        }

        return $result;
    }

    /**
     * Determine media type: 'image', 'video', 'gif'.
     */
    protected function determineMediaType(string $extension, string $mimeType): string
    {
        if ($extension === 'gif' || str_contains($mimeType, 'gif')) {
            return 'gif';
        }

        $videoExtensions = ['mp4', 'mov', 'webm', 'avi', 'mkv', 'flv', 'wmv', 'm4v'];
        if (in_array($extension, $videoExtensions) || str_starts_with($mimeType, 'video/')) {
            return 'video';
        }

        return 'image';
    }

    /**
     * Ensure storage directories exist.
     */
    protected function ensureDirectories(): void
    {
        $dirs = ['original', 'large', 'small', 'thumb', 'gif', 'videos'];
        foreach ($dirs as $dir) {
            $path = storage_path('app/public/media/' . $dir);
            if (!File::isDirectory($path)) {
                File::makeDirectory($path, 0755, true);
            }
        }
    }
}
