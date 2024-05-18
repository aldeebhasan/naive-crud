<?php

namespace Aldeebhasan\NaiveCrud\Test\Feature;

use Aldeebhasan\NaiveCrud\Logic\Managers\FileManager;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FileOperationTest extends FeatureTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->login();
    }

    public function test_upload_image()
    {
        $name = 'sample.jpg';
        $path = __DIR__.'/../Sample/stubs/'.$name;
        $file = new UploadedFile($path, $name, 'image/jpg', null, true);

        $route = route('api.files.image');
        $response = $this->post($route, [
            'file' =>  $file,
            'resource' => 'general',
        ]);
        $response->assertOk();
        $filePath = FileManager::make()->getStoragePath('general').'/'.$response->json('data.name');
        Storage::assertExists($filePath);
    }

    public function test_upload_file()
    {
        $name = 'sample.pdf';
        $path = __DIR__.'/../Sample/stubs/'.$name;
        $file = new UploadedFile($path, $name, 'application/pdf', null, true);

        $route = route('api.files.file');
        $response = $this->post($route, [
            'file' =>  $file,
            'resource' => 'files',
        ]);
        $response->assertOk();
        $filePath = FileManager::make()->getStoragePath('files').'/'.$response->json('data.name');
        Storage::assertExists($filePath);
    }
}
