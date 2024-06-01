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

class SearchOperationTest extends FeatureTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->login();
    }

    public function test_search_without_authorization()
    {
        $route = route('api.blogs.search');
        $response = $this->get($route);
        $response->assertStatus(403);
    }

    public function test_search_with_authorization()
    {
        Gate::define('index_blogs', fn () => true);
        $route = route('api.blogs.search');
        $response = $this->get($route);
        $response->assertStatus(200);
    }

    public function test_search_with_data()
    {
        $items = Blog::factory()->times(5)->create();
        Gate::define('index_blogs', fn () => true);
        $route = route('api.blogs.search');
        $response = $this->get($route);
        $response->assertStatus(200);
        $response->assertJsonCount($items->count(), 'data.items');
        $response->assertJsonStructure([
            'data' => [
                'items' => [
                    '*' => array_keys($items->first()->toArray()),
                ],
                'meta' => ['has_more_page'],
            ],
        ]);
    }

    public function test_index_with_data_and_custom_resource()
    {
        app()->bind(
            ComponentResolver::class,
            function () {
                $componentsResolverMock = \Mockery::mock(ComponentResolver::make(Blog::class))->makePartial();
                $componentsResolverMock->shouldReceive('resolveModelResource')->once()->andReturn(BlogResource::class);

                return $componentsResolverMock;
            }
        );

        $items = Blog::factory()->times(5)->create();
        Gate::define('index_blogs', fn () => true);
        $route = route('api.blogs.search');
        $response = $this->get($route);
        $response->assertStatus(200);
        $response->assertJsonCount($items->count(), 'data.items');
        $response->assertJsonStructure([
            'data' => [
                'items' => [
                    '*' => ['key', 'value'],
                ],
                'meta' => ['has_more_page'],
            ],
        ]);
    }

    public function test_index_with_filters()
    {
        $filter = new class implements FilterUI {
            public function fields(): array
            {
                return [
                    new FilterField(field: 'title', operator: '='),
                ];
            }
        };
        app()->bind(
            BlogController::class,
            function () use ($filter) {
                $componentsResolverMock = \Mockery::mock(BlogController::class)->makePartial();
                $componentsResolverMock->shouldReceive('resolveUser')->andReturn($this->user);
                $componentsResolverMock->shouldReceive('fullQueryResolver')->once()->andReturn(
                    QueryResolver::make(Blog::class, $componentsResolverMock->baseQuery(...))
                        ->setFilters([$filter])
                );

                return $componentsResolverMock;
            }
        );

        $items = Blog::factory()->times(5)->create();
        Gate::define('index_blogs', fn () => true);
        $route = route('api.blogs.search', ['filters' => ['title' => $items->first()->title]]);
        $response = $this->get($route);
        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data.items');
    }

    public function test_index_with_sorts()
    {
        $sorter = new class implements SortUI {
            public function fields(): array
            {
                return [
                    new SortField(field: 'title', defaultDirection: 'asc'),
                ];
            }
        };
        app()->bind(
            BlogController::class,
            function () use ($sorter) {
                $componentsResolverMock = \Mockery::mock(BlogController::class)->makePartial();
                $componentsResolverMock->shouldReceive('resolveUser')->andReturn($this->user);
                $componentsResolverMock->shouldReceive('fullQueryResolver')->once()->andReturn(
                    QueryResolver::make(Blog::class, $componentsResolverMock->baseQuery(...))
                        ->setSorters($sorter)
                );

                return $componentsResolverMock;
            }
        );

        $items = Blog::factory()->times(5)->create();
        Gate::define('index_blogs', fn () => true);
        $route = route('api.blogs.search', ['sorts' => ['title' => 'desc']]);
        $response = $this->get($route);
        $response->assertStatus(200);
        $response->assertJsonCount($items->count(), 'data.items');
        $firstId = $response->json('data.items.0.title');
        self::assertEquals($items->sortByDesc('title')->first()->title, $firstId);
    }
}
