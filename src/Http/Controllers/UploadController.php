<?php

namespace Aldeebhasan\NaiveCrud\Http\Controllers;

use Aldeebhasan\NaiveCrud\Contracts\FilterUI;
use Aldeebhasan\NaiveCrud\Contracts\SortUI;
use Aldeebhasan\NaiveCrud\Traits\Crud\DeleteTrait;
use Aldeebhasan\NaiveCrud\Traits\Crud\HooksTrait;
use Aldeebhasan\NaiveCrud\Traits\Crud\IndexTrait;
use Aldeebhasan\NaiveCrud\Traits\Crud\ResponseTrait;
use Aldeebhasan\NaiveCrud\Traits\Crud\SearchTrait;
use Aldeebhasan\NaiveCrud\Traits\Crud\ShowTrait;
use Aldeebhasan\NaiveCrud\Traits\Crud\StoreTrait;
use Aldeebhasan\NaiveCrud\Traits\Crud\UpdateTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

abstract class UploadController extends Controller
{


    public function image(Request $request)
    {
        $max = config('max_file_size');
        $validated = $request->validate([
            'file' => "required|max:{$max}|mimes:jpeg,bmp,png,jpg,gif,svg,avif,webm",
            'resource' => 'required|string',
        ]);
        $file = $request->file('file');
    }


    public function file(Request $request)
    {

    }


    private function upload($request, $file)
    {




        $mime = $file->getMimeType();
        Storage::makeDirectory('files');

        $name = time() . uniqid();
        $nameWithExtension = $name . "." . $file->getClientOriginalExtension();

        $saveBase = in_array(config('filesystems.default'), ['s3', 'local']) ? 'public/' : '';
        $base = '';
        if (config('filesystems.default') == 'local') {
            $base = 'storage/';
        } elseif (config('filesystems.default') == 's3') {
            $base = 'public/';
        }
        $name = "files/$nameWithExtension";
        Storage::put("{$saveBase}{$name}", file_get_contents($file), 'public');
        $path = "{$base}{$name}";

        return [
            'relative_url' => $path,
            'absolute_url' => imageUrl($path),
            'extension' => $file->getClientOriginalExtension(),
            'mime' => $mime,
            'file_size' => [
                'value' => ($file->getSize() / 1024),
                'unit' => 'KB'
            ],
        ];


    }
}
