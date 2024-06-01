<?php

namespace Aldeebhasan\NaiveCrud\Test\Feature;

use Aldeebhasan\NaiveCrud\Test\Sample\App\Models\Comment;
use Aldeebhasan\NaiveCrud\Test\TestUI;
use Aldeebhasan\NaiveCrud\Test\Traits\DeleteTestTrait;
use Aldeebhasan\NaiveCrud\Test\Traits\ExportTestTrait;
use Aldeebhasan\NaiveCrud\Test\Traits\ImportTestTrait;
use Aldeebhasan\NaiveCrud\Test\Traits\IndexTestTrait;
use Aldeebhasan\NaiveCrud\Test\Traits\SearchTestTrait;
use Aldeebhasan\NaiveCrud\Test\Traits\ShowTestTrait;
use Aldeebhasan\NaiveCrud\Test\Traits\StoreTestTrait;
use Aldeebhasan\NaiveCrud\Test\Traits\UpdateTestTrait;
use Illuminate\Database\Eloquent\Factories\Factory;

class FullCrudOperationTest extends FeatureTestCase implements TestUI
{
    use StoreTestTrait, UpdateTestTrait, ShowTestTrait, SearchTestTrait, IndexTestTrait,
        ImportTestTrait, ExportTestTrait, DeleteTestTrait;

    public bool $exportAllShouldQueue = true;

    protected function setUp(): void
    {
        parent::setUp();
        $this->login();

    }

    public function getResource(): string
    {
        return 'comments';
    }

    public function getResourcePrefix(): string
    {
        return 'api.comments.';
    }

    public function generalStateParameters(): array
    {
        return [];
    }

    public function generalRouteParameters(): array
    {
        return [];
    }

    public function getModel(): string
    {
        return Comment::class;
    }

    public function customFactory(): Factory
    {
        return $this->getModel()::factory();
    }
}
