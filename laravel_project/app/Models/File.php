<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class File extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'original_name',
        'file_path',
        'file_url',
        'file_size',
        'mime_type',
        'type',
        'description',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'file_size' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        // Add any sensitive fields here if needed
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'human_readable_size',
        'is_image',
        'extension',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Auto-delete file from storage when model is deleted
        static::deleting(function ($file) {
            if ($file->file_path && Storage::disk('public')->exists($file->file_path)) {
                Storage::disk('public')->delete($file->file_path);
            }
        });
    }

    /**
     * Get human readable file size.
     *
     * @return string
     */
    public function getHumanReadableSizeAttribute(): string
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Check if file is an image.
     *
     * @return bool
     */
    public function getIsImageAttribute(): bool
    {
        return $this->type === 'image' || str_starts_with($this->mime_type, 'image/');
    }

    /**
     * Get file extension.
     *
     * @return string|null
     */
    public function getExtensionAttribute(): ?string
    {
        return pathinfo($this->original_name, PATHINFO_EXTENSION);
    }

    /**
     * Scope to filter by file type.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope to filter by mime type.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $mimeType
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfMimeType($query, string $mimeType)
    {
        return $query->where('mime_type', $mimeType);
    }

    /**
     * Scope to filter images only.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeImages($query)
    {
        return $query->where('type', 'image')
                    ->orWhere('mime_type', 'like', 'image/%');
    }

    /**
     * Scope to filter documents only.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDocuments($query)
    {
        return $query->where('type', 'document');
    }

    /**
     * Get the full file path for storage operations.
     *
     * @return string|null
     */
    public function getStoragePath(): ?string
    {
        return $this->file_path ? storage_path('app/public/' . $this->file_path) : null;
    }

    /**
     * Check if file exists in storage.
     *
     * @return bool
     */
    public function fileExists(): bool
    {
        return $this->file_path && Storage::disk('public')->exists($this->file_path);
    }

    /**
     * Get file content.
     *
     * @return string|null
     */
    public function getContent(): ?string
    {
        if (!$this->fileExists()) {
            return null;
        }

        return Storage::disk('public')->get($this->file_path);
    }

    /**
     * Relationships
     */

    // Example: If files belong to users
    // public function user()
    // {
    //     return $this->belongsTo(User::class);
    // }

    // Example: If files can be associated with multiple models (polymorphic)
    // public function fileable()
    // {
    //     return $this->morphTo();
    // }
}