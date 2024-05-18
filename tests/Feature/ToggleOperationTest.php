<?php

namespace Aldeebhasan\NaiveCrud\Test\Feature;

use Aldeebhasan\NaiveCrud\Logic\Resolvers\ComponentResolver;
use Aldeebhasan\NaiveCrud\Test\Sample\App\Http\Requests\BlogRequest;
use Aldeebhasan\NaiveCrud\Test\Sample\App\Models\Blog;
use Illuminate\Support\Facades\Gate;

class ToggleOperationTest extends FeatureTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->login();
    }

    public function test_toggle_without_authorization()
    {
        $blogs = factory(Blog::class)->times(2)->create();
        $route = route('api.blogs.toggle');
        $response = $this->put($route, ['resources' => $blogs->pluck('id')->toArray()]);
        $response->assertStatus(403);
    }

    public function test_update_with_missing_data()
    {
        $blogs = factory(Blog::class)->times(2)->create();
        Gate::define('update_blogs', fn () => true);
        $route = route('api.blogs.toggle');
        $response = $this->put($route, ['resources' => $blogs->pluck('id')->toArray()]);
        $response->assertOk();
    }

    public function test_update_with_full_data()
    {
        app()->bind(
            ComponentResolver::class,
            function () {
                $componentsResolverMock = \Mockery::mock(ComponentResolver::make(Blog::class))->makePartial();
                $componentsResolverMock->shouldReceive('resolveRequestForm')->once()->andReturn(BlogRequest::class);

                return $componentsResolverMock;
            }
        );

        $blogs = factory(Blog::class)->times(2)->create();
        Gate::define('update_blogs', fn () => true);
        $route = route('api.blogs.toggle');
        $data = [
            'resources' => $blogs->pluck('id')->toArray(),
            'active' => false,
        ];
        $response = $this->put($route, $data);
        $response->assertOk();
        $this->assertDatabaseHas(Blog::class, ['id' => $blogs->first()->id, 'active' => false]);
        $this->assertDatabaseHas(Blog::class, ['id' => $blogs->last()->id, 'active' => false]);
    }
}
