<?php

namespace Aldeebhasan\NaiveCrud\Http\Controllers;

use Aldeebhasan\NaiveCrud\Logic\Managers\FileManager;
use Aldeebhasan\NaiveCrud\Traits\Crud\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class UploadController extends Controller
{
    use ResponseTrait;

    public function image(Request $request, FileManager $fileManager): JsonResponse
    {
        $max = config('naive-crud.image_max_size');
        $extensions = config('naive-crud.image_extensions');
        $validated = $request->validate([
            'file' => "required|max:{$max}|mimes:$extensions",
            'resource' => 'required|string',
        ]);
        $file = $request->file('file');

        $fileManager->setPath($validated['resource'])->setFile($file);
        if (config('naive-crud.image_thumbnail', false)) {
            $fileManager->thumbnail(config('naive-crud.image_thumbnail_width'));
        }
        if (config('naive-crud.image_resize', true)) {
            $fileManager->resize(config('naive-crud.image_width'), config('naive-crud.image_height'));
        }
        $info = $fileManager->uploadImage();

        return $this->success(__('NaiveCrud::messages.uploaded'), $info);
    }

    public function file(Request $request, FileManager $fileManager): JsonResponse
    {
        $max = config('naive-crud.file_max_size');
        $extensions = config('naive-crud.file_extensions');
        $validated = $request->validate([
            'file' => "required|max:{$max}|mimes:$extensions",
            'resource' => 'required|string',
        ]);
        $file = $request->file('file');
        $info = $fileManager->setPath($validated['resource'])->setFile($file)->uploadFile();

        return $this->success(__('NaiveCrud::messages.uploaded'), $info);
    }
}
