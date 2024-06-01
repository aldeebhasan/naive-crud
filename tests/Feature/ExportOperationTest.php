<?php

namespace Aldeebhasan\NaiveCrud\Test\Feature;

use Aldeebhasan\NaiveCrud\Test\Sample\App\Models\Blog;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;

class ExportOperationTest extends FeatureTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->login();
    }

    public function test_export_without_authorization()
    {
        $route = route('api.blogs.export');
        $response = $this->get($route);
        $response->assertStatus(403);
    }

    public function test_export_page_success()
    {
        Blog::factory()->times(5)->create();
        Excel::fake();
        Gate::define('export_blogs', fn () => true);
        $route = route('api.blogs.export');
        $response = $this->get($route);
        $response->assertOk();
        Excel::assertDownloaded('blogs_page.csv');
    }

    public function test_export_all_success()
    {
        Blog::factory()->times(5)->create();
        Excel::fake();
        Gate::define('export_blogs', fn () => true);
        $route = route('api.blogs.export', ['target' => 'all']);
        $response = $this->get($route);
        $response->assertOk();
        Excel::assertDownloaded('blogs_all.csv');
    }
}
