<?php

namespace Aldeebhasan\NaiveCrud\Test\Feature;

use Aldeebhasan\NaiveCrud\Test\Sample\App\Models\Blog;
use Illuminate\Support\Facades\Gate;

class DeleteOperationTest extends FeatureTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->login();
    }

    public function test_delete_without_authorization()
    {
        $blog = factory(Blog::class)->create();
        $route = route('api.blogs.destroy', ['blog' => $blog->id]);
        $response = $this->delete($route);
        $response->assertStatus(403);
    }

    public function test_delete_successfully()
    {
        $blog = factory(Blog::class)->create();
        Gate::define('delete_blogs', fn () => true);
        $route = route('api.blogs.destroy', ['blog' => $blog->id]);
        $response = $this->delete($route);
        $response->assertOk();
    }

    public function test_bulk_delete()
    {
        $blogs = factory(Blog::class)->times(2)->create();

        Gate::define('delete_blogs', fn () => true);
        $resources = ['resources' => $blogs->pluck('id')->toArray()];
        $route = route('api.blogs.bulkDestroy');
        $response = $this->delete($route, $resources);
        $response->assertOk();
        $this->assertSoftDeleted('blogs', $blogs->only('id')->toArray());
        self::assertEquals($response->json('message'), __('NaiveCrud::messages.bulk-deleted', ['count' => 2]));
    }
}
