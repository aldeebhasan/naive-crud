<?php

namespace Aldeebhasan\NaiveCrud\Test\Unit;

use Aldeebhasan\NaiveCrud\Logic\Managers\FileManager;
use Aldeebhasan\NaiveCrud\Test\TestCase;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ResourceTest extends TestCase
{
    public function test_resourse()
    {
//        $file = file_get_contents("https://static.remove.bg/sample-gallery/graphics/bird-thumbnail.jpg");
        $file = storage_path('app/public/image.jpg');
        $file = new UploadedFile($file, 'image.jpg');
        $file = \Illuminate\Http\UploadedFile::createFromBase($file);
        FileManager::make()->setPath('images')->uploadImage($file);
    }

    protected function indexQuery(Builder $query): Builder
    {
        return $query->where('id', 10);
    }

    protected function searchQuery(Builder $query, string $value): Builder
    {
        return $query->where('name', $value);
    }

    public function test_resourse2()
    {
        $res = $this->get('/api/blogs')->assertStatus(200);
    }
}

class TestModel extends Model
{
    protected $table = 'products';
}

class Req extends FormRequest
{
    public function rules(): array
    {
        return [
            'username' => 'required',
        ];
    }
}
