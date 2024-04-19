<?php

namespace Aldeebhasan\NaiveCrud\Test\Unit;

use Aldeebhasan\NaiveCrud\Http\Resources\BaseResource;
use Aldeebhasan\NaiveCrud\Test\TestCase;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;

class ResourceTest extends TestCase
{

    function test_resourse()
    {
        dump(BaseResource::make(new TestModel())->additional(['1' => "Hassan"])->resolve());
        dump(BaseResource::collection(collect([new TestModel()]))->additional(['1' => "Hassan"])->resolve());
    }

    function test_resourse2()
    {
        request()->merge(['username'=>'hasan','name'=>'alu']);
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