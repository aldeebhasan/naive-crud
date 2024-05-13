<?php

namespace Aldeebhasan\NaiveCrud\Test\Feature;

use Aldeebhasan\NaiveCrud\Contracts\FilterUI;
use Aldeebhasan\NaiveCrud\Contracts\SortUI;
use Aldeebhasan\NaiveCrud\DTO\FilterField;
use Aldeebhasan\NaiveCrud\DTO\SortField;
use Aldeebhasan\NaiveCrud\Logic\Resolvers\ComponentResolver;
use Aldeebhasan\NaiveCrud\Logic\Resolvers\QueryResolver;
use Aldeebhasan\NaiveCrud\Test\Sample\App\Http\Controllers\BlogController;
use Aldeebhasan\NaiveCrud\Test\Sample\App\Http\Resources\BlogResource;
use Aldeebhasan\NaiveCrud\Test\Sample\App\Models\Blog;
use Illuminate\Support\Facades\Gate;

class ShowOperationTest extends FeatureTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->login();
        $this->blog = factory(Blog::class)->create(['user_id' => $this->user->id]);
    }

    public function test_show_without_authorization()
    {
        $route = route('api.blogs.show', ['blog' => $this->blog->id]);
        $response = $this->get($route);
        $response->assertStatus(403);
    }

    public function test_show_with_authorization()
    {
        Gate::define('show_blogs', fn() => true);
        $route = route('api.blogs.show', ['blog' => $this->blog->id]);
        $response = $this->get($route);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => array_keys($this->blog->toArray()),
        ]);
    }


    public function test_show_with_custom_resource()
    {
        app()->bind(
            ComponentResolver::class,
            function () {
                $componentsResolverMock = \Mockery::mock(ComponentResolver::make(Blog::class))->makePartial();
                $componentsResolverMock->shouldReceive('resolveModelResource')->once()->andReturn(BlogResource::class);

                return $componentsResolverMock;
            }
        );

        Gate::define('show_blogs', fn() => true);
        $route = route('api.blogs.show', ['blog' => $this->blog->id]);
        $response = $this->get($route);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => ['id', 'slug', 'title', 'description'],
        ]);
    }
}
