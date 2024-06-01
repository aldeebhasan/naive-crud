<?php

namespace Aldeebhasan\NaiveCrud\Test\Feature;

use Aldeebhasan\NaiveCrud\Test\Sample\App\Models\Blog;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;

class ImportOperationTest extends FeatureTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->login();
    }

    public function test_import_template_without_authorization()
    {
        $route = route('api.blogs.importTemplate');
        $response = $this->get($route);
        $response->assertStatus(403);
    }

    public function test_export_template_success()
    {
        Excel::fake();
        Gate::define('import_blogs', fn () => true);
        $route = route('api.blogs.importTemplate');
        $response = $this->get($route);
        $response->assertOk();
        Excel::assertDownloaded('blogs-template.csv');
    }

    public function test_import()
    {
        $file = 'public/tmp/blogs.csv';
        collect([
            ['title', 'description', 'image'],
            ['title here', 'description here', null],
            ['title here2', 'description here2', null],
        ])->storeExcel($file);
        Gate::define('import_blogs', fn () => true);
        $route = route('api.blogs.import');
        $response = $this->post($route, [
            'file' => storage_path('app/'.$file),
        ]);
        $response->assertOk();
        $this->assertDatabaseCount(Blog::class, 2);
    }
}
