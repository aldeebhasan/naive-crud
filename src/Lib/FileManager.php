<?php

namespace Aldeebhasan\NaiveCrud\Lib;

use Aldeebhasan\NaiveCrud\Traits\Makable;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;

class FileManager
{
    use Makable;

    protected string $path = '';

    protected string $fileName = '';

    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function uploadImage(UploadedFile $file, string $name = ''): array
    {
        $extension = strtolower($file->getClientOriginalExtension()) === 'png' ?: 'jpeg';
        $mime = $file->getMimeType();
        $file = file_get_contents($file);

        $image = ImageManager::imagick()->read($file);
        $width = config('naive-crud.image_max_width');
        $height = config('naive-crud.image_max_height');
        $image = $image->scaleDown($width, $height);
        $encoded = $image->encodeByExtension($extension, quality: 75)->toString();

        $response = $this->upload($encoded, $name, $extension, $mime);

        if (config('naive-crud.image_thumbnail', false)) {
            $image = ImageManager::imagick()->read($file);
            $width = config('naive-crud.image_thumbnail_width');
            $image = $image->cover($width, $width);
            $encoded = $image->encodeByExtension($extension, quality: 75)->toString();

            $thumbnailName = 'thumbnails/'.pathinfo($response['url'], PATHINFO_FILENAME);
            $this->upload($encoded, $thumbnailName, $extension, $mime);
        }

        return $response;
    }

    public function uploadFile(UploadedFile $file, string $name = ''): array
    {
        $extension = $file->getClientOriginalExtension();
        $mime = $file->getMimeType();
        $file = file_get_contents($file);

        return $this->upload($file, $name, $extension, $mime);
    }

    private function upload(string $file, string $name = '', string $extension = '', string $mime = ''): array
    {
        $base = $this->getBasePath();

        $name = $name ?: (time().uniqid());
        $name = "$name.$extension";
        $path = "$this->path/$name";
        Storage::put("public/{$path}", $file, 'public');
        $path = "{$base}{$path}";

        return [
            'url' => asset($path),
            'meta' => [
                'extension' => $extension,
                'mime' => $mime,
            ],
        ];
    }

    public function getBasePath(): string
    {
        $base = '';
        if (config('filesystems.default') === 'local') {
            $base = 'storage/';
        } elseif (config('filesystems.default') === 's3') {
            $base = 'public/';
        }

        return $base;
    }
}
