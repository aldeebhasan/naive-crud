<?php

namespace Aldeebhasan\NaiveCrud\Logic\Managers;

use Aldeebhasan\NaiveCrud\Traits\Makable;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;

class FileManager
{
    use Makable;

    protected string $path = '';

    protected string $fileName = '';

    protected bool $thumbnail = false;

    protected array $thumbnailDim = [];

    protected UploadedFile $file;

    public function setFile(UploadedFile $file): self
    {
        $this->file = $file;

        return $this;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function withThumbnail(int $width, ?int $height = null): self
    {
        $this->thumbnail = true;
        $this->thumbnailDim = [
            'width' => $width,
            'height' => $height ?? $width,
        ];

        return $this;
    }

    public function uploadImage(string $name = ''): array
    {
        $extension = strtolower($this->file->getClientOriginalExtension()) === 'png' ?: 'jpeg';
        $file = file_get_contents($this->file);

        $image = ImageManager::imagick()->read($file);
        $width = config('naive-crud.image_max_width');
        $height = config('naive-crud.image_max_height');
        $image = $image->scaleDown($width, $height);
        $encoded = $image->encodeByExtension($extension, quality: 75)->toString();

        $name = $name ?: (time().uniqid());
        $response = $this->upload($encoded, $name, $extension);

        $this->generateThumbnail($file, $name, $extension);

        return $response;
    }

    protected function generateThumbnail(string $file, string $name, string $extension): void
    {
        if (! $this->thumbnail) {
            return;
        }

        $image = ImageManager::imagick()->read($file);
        $image = $image->cover($this->thumbnailDim['width'], $this->thumbnailDim['height']);
        $encoded = $image->encodeByExtension($extension, quality: 75)->toString();

        $thumbnailName = "thumbnails/$name";
        $this->upload($encoded, $thumbnailName, $extension);
    }

    public function uploadFile(string $name = ''): array
    {
        $extension = $this->file->getClientOriginalExtension();
        $file = file_get_contents($this->file);

        return $this->upload($file, $name, $extension);
    }

    private function upload(string $file, string $name, string $extension): array
    {
        $name = $name ?: (time().uniqid());
        $name = "$name.$extension";
        $path = "$this->path/$name";
        Storage::put($this->getStoragePath($path), $file, 'public');
        $path = $this->getAssetPath($path);

        return [
            'url' => asset($path),
            'name' => $name,
            'meta' => [
                'extension' => $extension,
                'mime' => $this->file->getMimeType(),
            ],
        ];
    }

    public function getAssetPath(string $path = ''): string
    {
        if (config('filesystems.default') === 's3') {
            $base = "public/$path";
        } else {
            $base = "storage/$path";
        }

        return $base;
    }

    public function getStoragePath(string $path = ''): string
    {
        return "public/{$path}";
    }
}
