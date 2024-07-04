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

    protected bool $resize = false;

    protected array $imageDim = [];

    protected array $thumbnailDim = [];

    protected string $file;

    protected array $meta = [
        'mime' => 'image/jpeg',
        'size' => '1024',
        'extension' => 'jpeg',
    ];

    public function setFile(UploadedFile|string $file): self
    {
        if ($file instanceof UploadedFile) {
            $this->meta = [
                'mime' => $file->getMimeType(),
                'size' => $file->getSize(),
                'extension' => strtolower($file->getClientOriginalExtension()) === 'png' ? 'png' : 'jpeg',
            ];
            $this->file = file_get_contents($file);
        } else {
            if (str($file)->startsWith('http')) {
                $this->file = file_get_contents($file);
            } else {
                $file = $this->getStoragePath($file);
                $this->file = Storage::read($file);
            }
        }

        return $this;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function thumbnail(int $width, ?int $height = null): self
    {
        $this->thumbnail = true;
        $this->thumbnailDim = [
            'width' => $width,
            'height' => $height ?? $width,
        ];

        return $this;
    }

    public function resize(int $width, ?int $height = null): self
    {
        $this->resize = true;
        $this->imageDim = [
            'width' => $width,
            'height' => $height ?? null,
        ];

        return $this;
    }

    public function uploadImage(string $name = ''): array
    {
        $image = ImageManager::imagick()->read($this->file);
        if ($this->resize) {
            $image = $image->scaleDown($this->imageDim['width'], $this->imageDim['height']);
        }
        $encoded = $image->encodeByExtension($this->meta['extension'], quality: 75)->toString();

        $name = $name ?: (time().uniqid());
        $response = $this->upload($encoded, $name);

        $this->generateThumbnail($name);

        return $response;
    }

    protected function generateThumbnail(string $name): void
    {
        if (! $this->thumbnail) {
            return;
        }

        $image = ImageManager::imagick()->read($this->file);
        $image = $image->cover($this->thumbnailDim['width'], $this->thumbnailDim['height']);
        $encoded = $image->encodeByExtension($this->meta['extension'], quality: 75)->toString();

        $thumbnailName = "thumbnails/$name";
        $this->upload($encoded, $thumbnailName);
    }

    public function uploadFile(string $name = ''): array
    {

        return $this->upload($this->file, $name);
    }

    private function upload(string $file, string $name): array
    {
        $name = $name ?: (time().uniqid());
        $name = "$name.".$this->meta['extension'];
        $path = "$this->path/$name";
        Storage::put($this->getStoragePath($path), $file, 'public');
        $path = $this->getAssetPath($path);

        return [
            'url' => asset($path),
            'name' => $name,
            'meta' => [
                'extension' => $this->meta['extension'],
                'mime' => $this->meta['mime'],
            ],
            'size' => [
                'value' => round($this->meta['size'] / 1024, 3),
                'unit' => 'KB',
            ],
        ];
    }

    public function getAssetPath(string $path = ''): string
    {
        $path = $this->getStoragePath($path);

        return Storage::url($path);
    }

    public function getStoragePath(string $path = ''): string
    {
        return "public/{$path}";
    }
}
