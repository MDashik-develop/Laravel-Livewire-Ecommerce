<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'media';

    protected $fillable = [
        'user_id',
        'title',
        'path',
        'folder',
        'type',
        'extension',
        'mime_type',
        'size',
        'sizes',
    ];

    protected $casts = [
        'sizes' => 'array',
        'size' => 'integer',
    ];

    protected $appends = [
        'url',
        'urls',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get variant URLs based on media type.
     */
    public function getUrlsAttribute(): array
    {
        if ($this->type === 'image') {
            return [
                'original' => asset('storage/media/original/' . $this->path),
                'large'    => asset('storage/media/large/' . $this->path),
                'small'    => asset('storage/media/small/' . $this->path),
                'thumb'    => asset('storage/media/thumb/' . $this->path),
                'gif'      => null,
                'video'    => null,
            ];
        }

        if ($this->type === 'gif') {
            return [
                'original' => asset('storage/media/gif/' . $this->path),
                'large'    => null,
                'small'    => null,
                'thumb'    => null,
                'gif'      => asset('storage/media/gif/' . $this->path),
                'video'    => null,
            ];
        }

        if ($this->type === 'video') {
            return [
                'original' => asset('storage/media/videos/' . $this->path),
                'large'    => null,
                'small'    => null,
                'thumb'    => null,
                'gif'      => null,
                'video'    => asset('storage/media/videos/' . $this->path),
            ];
        }

        return [
            'original' => asset('storage/media/original/' . $this->path),
            'large'    => null,
            'small'    => null,
            'thumb'    => null,
            'gif'      => null,
            'video'    => null,
        ];
    }

    /**
     * Primary URL helper: thumbnail for image, direct url for gif/video.
     */
    public function getUrlAttribute(): string
    {
        if ($this->type === 'image') {
            return asset('storage/media/small/' . $this->path);
        }

        if ($this->type === 'gif') {
            return asset('storage/media/gif/' . $this->path);
        }

        if ($this->type === 'video') {
            return asset('storage/media/videos/' . $this->path);
        }

        return asset('storage/media/original/' . $this->path);
    }

    /**
     * Delete files from disk when model is force deleted permanently.
     */
    protected static function booted()
    {
        static::forceDeleted(function ($media) {
            $folders = ['original', 'large', 'small', 'thumb', 'gif', 'videos'];
            foreach ($folders as $folder) {
                $filePath = storage_path('app/public/media/' . $folder . '/' . $media->path);
                if (File::exists($filePath)) {
                    File::delete($filePath);
                }
            }
        });
    }
}
