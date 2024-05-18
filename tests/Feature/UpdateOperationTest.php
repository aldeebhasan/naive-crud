<?php

namespace Aldeebhasan\NaiveCrud\Test\Feature;

use Aldeebhasan\NaiveCrud\Logic\Resolvers\ComponentResolver;
use Aldeebhasan\NaiveCrud\Test\Sample\App\Http\Requests\BlogRequest;
use Aldeebhasan\NaiveCrud\Test\Sample\App\Http\Resources\BlogResource;
use Aldeebhasan\NaiveCrud\Test\Sample\App\Models\Blog;
use Illuminate\Support\Facades\Gate;

class UpdateOperationTest extends FeatureTestCase
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

    public function test_update_without_authorization()
    {
        $blog = factory(Blog::class)->create();
        $route = route('api.blogs.update', ['blog' => $blog->id]);
        $response = $this->put($route, $this->payload);
        $response->assertStatus(403);
    }

    public function test_update_with_missing_data()
    {
        $blog = factory(Blog::class)->create();
        Gate::define('update_blogs', fn () => true);
        $route = route('api.blogs.update', ['blog' => $blog->id]);
        $response = $this->put($route, $this->payload);
        $response->assertOk();
    }

    public function test_update_with_full_data()
    {
        $blog = factory(Blog::class)->create();
        app()->bind(
            ComponentResolver::class,
            function () {
                $componentsResolverMock = \Mockery::mock(ComponentResolver::make(Blog::class))->makePartial();
                $componentsResolverMock->shouldReceive('resolveRequestForm')->once()->andReturn(BlogRequest::class);

                return $componentsResolverMock;
            }
        );

        Gate::define('update_blogs', fn () => true);
        $route = route('api.blogs.update', ['blog' => $blog->id]);
        $response = $this->put($route, $this->payload);
        $response->assertOk();
        $response->assertJsonStructure([
            'data' => ['id', 'title', 'description'],
        ]);
    }

    public function test_update_with_custom_resource()
    {
        $blog = factory(Blog::class)->create();
        app()->bind(
            ComponentResolver::class,
            function () {
                $componentsResolverMock = \Mockery::mock(ComponentResolver::make(Blog::class))->makePartial();
                $componentsResolverMock->shouldReceive('resolveRequestForm')->once()->andReturn(BlogRequest::class);
                $componentsResolverMock->shouldReceive('resolveModelResource')->once()->andReturn(BlogResource::class);

                return $componentsResolverMock;
            }
        );

        Gate::define('update_blogs', fn () => true);
        $route = route('api.blogs.update', ['blog' => $blog->id]);
        $response = $this->put($route, $this->payload);
        $response->assertOk();
        $response->assertJsonStructure([
            'data' => ['id', 'slug', 'title', 'description'],
        ]);
    }

    public function test_bulk_update_with_full_data()
    {
        $blogs = factory(Blog::class)->times(2)->create();
        app()->bind(
            ComponentResolver::class,
            function () {
                $componentsResolverMock = \Mockery::mock(ComponentResolver::make(Blog::class))->makePartial();
                $componentsResolverMock->shouldReceive('resolveRequestForm')->once()->andReturn(BlogRequest::class);

                return $componentsResolverMock;
            }
        );

        Gate::define('update_blogs', fn () => true);
        $resources = ['resources' => $blogs->mapWithKeys(fn ($item) => [$item->id => $this->payload])->toArray()];
        $route = route('api.blogs.bulkUpdate');
        $response = $this->put($route, $resources);
        $response->assertOk();
        $this->assertDatabaseCount('blogs', 2);
        self::assertEquals($response->json('message'), __('NaiveCrud::messages.bulk-updated', ['count' => 2]));
    }
}
