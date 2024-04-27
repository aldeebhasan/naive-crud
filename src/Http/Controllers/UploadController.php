<?php

namespace Aldeebhasan\NaiveCrud\Http\Controllers;

use Aldeebhasan\NaiveCrud\Lib\FileManager;
use Aldeebhasan\NaiveCrud\Traits\Crud\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\UploadedFile;

abstract class UploadController extends Controller
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
        $info = $fileManager->setPath($validated['resource'])->uploadImage($file);

        return $this->success(__('NaiveCrud::messages.uploaded'), $info);
    }

    public function file(Request $request, FileManager $fileManager): JsonResponse
    {
        $max = config('naive-crud.file_max_size');
        $extensions = config('naive-crud.file_extensions');
        $validated = $request->validate([
            'file' => "required|max:{$max}|$extensions",
            'resource' => 'required|string',
        ]);
        $file = $request->file('file');
        $info = $fileManager->setPath($validated['resource'])->uploadFile($file);

        return $this->success(__('NaiveCrud::messages.uploaded'), $info);
    }

}
