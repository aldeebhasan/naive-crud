<?php

namespace Aldeebhasan\NaiveCrud\Test\Feature;

use Aldeebhasan\NaiveCrud\Test\Base\TestUI;
use Aldeebhasan\NaiveCrud\Test\Base\Traits\DeleteTestTrait;
use Aldeebhasan\NaiveCrud\Test\Base\Traits\ExportTestTrait;
use Aldeebhasan\NaiveCrud\Test\Base\Traits\ImportTestTrait;
use Aldeebhasan\NaiveCrud\Test\Base\Traits\IndexTestTrait;
use Aldeebhasan\NaiveCrud\Test\Base\Traits\SearchTestTrait;
use Aldeebhasan\NaiveCrud\Test\Base\Traits\ShowTestTrait;
use Aldeebhasan\NaiveCrud\Test\Base\Traits\StoreTestTrait;
use Aldeebhasan\NaiveCrud\Test\Base\Traits\UpdateTestTrait;
use Aldeebhasan\NaiveCrud\Test\Sample\App\Models\Comment;
use Illuminate\Database\Eloquent\Factories\Factory;

class FullCrudOperationTest extends FeatureTestCase implements TestUI
{
    use StoreTestTrait, UpdateTestTrait, ShowTestTrait, SearchTestTrait, IndexTestTrait,
        ImportTestTrait, ExportTestTrait, DeleteTestTrait;

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
