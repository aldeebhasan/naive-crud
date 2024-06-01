<?php

namespace Aldeebhasan\NaiveCrud\Test\Feature;

use Aldeebhasan\NaiveCrud\Logic\Resolvers\ComponentResolver;
use Aldeebhasan\NaiveCrud\Test\Sample\App\Http\Requests\BlogRequest;
use Aldeebhasan\NaiveCrud\Test\Sample\App\Http\Resources\BlogResource;
use Aldeebhasan\NaiveCrud\Test\Sample\App\Models\Blog;
use Illuminate\Support\Facades\Gate;

class CreateOperationTest extends FeatureTestCase
{
    private array $payload = [
        'title' => 'title here',
        'description' => 'description here',
        'image' => null,
    ];

    protected function setUp(): void
    {
        parent::setUp();
        $this->login();
    }

    public function test_create_without_authorization()
    {

        $route = route('api.blogs.store');
        $response = $this->post($route, $this->payload);
        $response->assertStatus(403);
    }

    public function test_create_with_data()
    {
        Gate::define('create_blogs', fn () => true);
        $route = route('api.blogs.store');
        $response = $this->post($route, $this->payload);
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

        Gate::define('create_blogs', fn () => true);
        $route = route('api.blogs.store');
        $response = $this->post($route, $this->payload);
        $response->assertStatus(201);
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

        Gate::define('create_blogs', fn () => true);
        $route = route('api.blogs.store', $this->payload);
        $response = $this->post($route);
        $response->assertStatus(201);
        $response->assertJsonStructure([
            'data' => ['id', 'slug', 'title', 'description'],
        ]);
    }

    public function test_bulk_create_with_full_data()
    {
        app()->bind(
            ComponentResolver::class,
            function () {
                $componentsResolverMock = \Mockery::mock(ComponentResolver::make(Blog::class))->makePartial();
                $componentsResolverMock->shouldReceive('resolveRequestForm')->once()->andReturn(BlogRequest::class);

                return $componentsResolverMock;
            }
        );

        Gate::define('create_blogs', fn () => true);
        $resources = ['resources' => [$this->payload, $this->payload]];
        $route = route('api.blogs.bulkStore');
        $response = $this->post($route, $resources);
        $response->assertStatus(201);
        $this->assertDatabaseCount('blogs', 2);
        self::assertEquals($response->json('message'), __('NaiveCrud::messages.bulk-stored', ['count' => 2]));
    }
}
