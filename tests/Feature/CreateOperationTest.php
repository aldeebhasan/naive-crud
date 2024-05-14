<?php

namespace Aldeebhasan\NaiveCrud\Test\Feature;

use Aldeebhasan\NaiveCrud\Contracts\FilterUI;
use Aldeebhasan\NaiveCrud\Contracts\SortUI;
use Aldeebhasan\NaiveCrud\DTO\FilterField;
use Aldeebhasan\NaiveCrud\DTO\SortField;
use Aldeebhasan\NaiveCrud\Logic\Resolvers\ComponentResolver;
use Aldeebhasan\NaiveCrud\Logic\Resolvers\QueryResolver;
use Aldeebhasan\NaiveCrud\Test\Sample\App\Http\Controllers\BlogController;
use Aldeebhasan\NaiveCrud\Test\Sample\App\Http\Requests\BlogRequest;
use Aldeebhasan\NaiveCrud\Test\Sample\App\Http\Resources\BlogResource;
use Aldeebhasan\NaiveCrud\Test\Sample\App\Models\Blog;
use Illuminate\Support\Facades\Gate;

class CreateOperationTest extends FeatureTestCase
{
    private array $payload = [
        'title' => "title here",
        'description' => "description here",
        'image' => null
    ];

    protected function setUp(): void
    {
        parent::setUp();
        $this->login();
    }

    public function test_create_without_authorization()
    {

        $route = route('api.blogs.store', $this->payload);
        $response = $this->get($route);
        $response->assertStatus(403);
    }

    public function test_create_with_missing_data()
    {
        Gate::define('create_blogs', fn() => true);
        $route = route('api.blogs.store', $this->payload);
        $response = $this->post($route);
        $response->assertServerError();
    }

    public function test_create_with_full_data()
    {
        app()->bind(
            ComponentResolver::class,
            function () {
                $componentsResolverMock = \Mockery::mock(ComponentResolver::make(Blog::class))->makePartial();
                $componentsResolverMock->shouldReceive('resolveRequestForm')->once()->andReturn(BlogRequest::class);

                return $componentsResolverMock;
            }
        );

        Gate::define('create_blogs', fn() => true);
        $route = route('api.blogs.store', $this->payload);
        $response = $this->post($route);
        $response->assertStatus(201);
        $response->dump();
        $response->assertJsonStructure([
            'data' => ['id', 'title', 'description'],
        ]);
    }


    public function test_create_with_custom_resource()
    {
        app()->bind(
            ComponentResolver::class,
            function () {
                $componentsResolverMock = \Mockery::mock(ComponentResolver::make(Blog::class))->makePartial();
                $componentsResolverMock->shouldReceive('resolveRequestForm')->once()->andReturn(BlogRequest::class);
                $componentsResolverMock->shouldReceive('resolveModelResource')->once()->andReturn(BlogResource::class);

                return $componentsResolverMock;
            }
        );

        Gate::define('create_blogs', fn() => true);
        $route = route('api.blogs.store', $this->payload);
        $response = $this->post($route);
        $response->assertStatus(201);
        $response->dump();
        $response->assertJsonStructure([
            'data' => ['id', 'slug', 'title', 'description'],
        ]);
    }
}
