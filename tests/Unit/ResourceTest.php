<?php

namespace Aldeebhasan\NaiveCrud\Test\Unit;

use Aldeebhasan\NaiveCrud\Logic\Managers\FileManager;
use Aldeebhasan\NaiveCrud\Test\TestCase;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ResourceTest extends TestCase
{

    function test_resourse()
    {
//        $file = file_get_contents("https://static.remove.bg/sample-gallery/graphics/bird-thumbnail.jpg");
        $file = storage_path('app/public/image.jpg');
        $file = new UploadedFile($file, "image.jpg");
        $file = \Illuminate\Http\UploadedFile::createFromBase($file);
        FileManager::make()->setPath("images")->uploadImage($file);
    }

    function test_resourse2()
    {
        request()->merge(['username' => 'hasan', 'name' => 'alu']);
        $x = app(Req::class);
        dd($x->validated());
    }


}

class TestModel extends Model
{

}

class Req extends FormRequest
{
    function rules(): array
    {
        return [
            'username' => 'required'
        ];
    }
}