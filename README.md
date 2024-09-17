Laravel Naive Crud
=====
A lightweight package handle laravel api crud operations

Installation
------------

Install using composer:

```bash
composer require aldeebhasan/naive-crud
```

Optional: After installation run the following code to publish the config:

```
php artisan vendor:publish --tag=naive-crud
```

Basic Usage
-----------
This package will help yo to fully customize your crud and have a huge control over each part
of your development> No more duplicated code will appear.

Project structure
--
The best way to understand how the package works is by examples, lets suppose that we want to create a crud for Blogs

Assume that we have a model for blog as `App\Models\Blog.php`

### Controller:

```php
use Aldeebhasan\NaiveCrud\Http\Controllers\BaseController;

class BlogController extends BaseController
{
    //required: define the model class
    protected string $model = Blog::class;
    
    //optional: define the blog policy class
    protected string $policy = BlogPolicy::class;
    
     //optional: define the blog request class
     //The package is smart enough  to it will look for this class under app/http/Requests folder
    protected ?string $modelRequestForm = BlogRequest::class
    
    //optional: define the blog resource class
     //The package is smart enough  to it will look for this class under app/http/Resources folder
    protected ?string $modelResource = BlogResource::class
    
}

```

### Query handling:

We can control the access to data that need to be returned or exported using a list of re-defined query helpers

```php
    //applied over all the routes
    public function baseQuery(Builder $query): Builder
    {
        return $query;
    }
    //applied over  the index routes + along with base query
    protected function indexQuery(Builder $query): Builder
    {
        return $query;
    }
    //applied over  the show routes + along with base query
     protected function showQuery(Builder $query): Builder
    {
        return $query;
    }
    
      //applied over  the search routes + along with base query
     protected function searchQuery(Builder $query): Builder
    {
        return $query;
    }
    
    //applied over  the export routes + along with base query
     protected function exportQuery(Builder $query): Builder
    {
        return $query;
    }
    
    

```

### Routes:

We have two routes group

- Resource Routes: will enable the following routs:
    - [GET] /resource/search : search for items
    - [POST]/resource/import : import items from file import-template based file
    - [GET] /resource/import-template: download a template file to use for import
    - [GET] /resource/export: export the data
    - [POST] /resource/bulk : bulk store
    - [PUT] /resource/bulk  : bulk update
    - [DELETE] /resource/bulk: bulk delete
    - [GET] /resource/fields : get the filters and the sort fields
    - [PUT] /resource/toggle: toggle columns value
    - [GET] /resource : browse the paginated data of the model
    - [POST] /resource, : add new item
    - [GET] /resource/{id} : get detail of item
    - [PUT] resource/{id}  : update specific item
    - [DELETE] resource/{id} : delete specific item
      ```php
       NCRoute::ncResource('blogs', BlogController::class);
      ```

- File helper Routes: will enable the following routs:
    - [POST]/base_path/upload-image : to upload an image
    - [POST]/base_path/upload-file : to upload an file

    ```php
     NCRoute::files('base_path');
    ```

### Authorization:

Authorization to access controller resource can be controller using `authorize` param, by default it is `True`

```php
use Aldeebhasan\NaiveCrud\Http\Controllers\BaseController;
class BlogController extends BaseController
{
    //required: define the model class
    protected string $model = Blog::class;
    protected bool $authorize = true;
    
    //optional: define the blog policy class
    protected string $policy = BlogPolicy::class;
    
}

//policy class
class BlogPolicy extends BasePolicy
{
}

```

We expect the user has a defined gates of the following form: action_resource,

In our example, if we want the user to have access to the index route, he should have the following ability:

```php
$user->can('index_blogs',Blog::class) // => true
```

Most of the rules and permissions pacjages define this policies for us based on the user assigned permissions

### Resources

By default the package will search for a resource that match the model name.
In our example, the package will look for `BlogResource` under namespace `App\\Http\\Resources`.
If not found, it will return the `$model->toArray()` as response to all the retrieve model routes

```php
use Aldeebhasan\NaiveCrud\Http\Controllers\BaseController;
class BlogController extends BaseController
{
    //required: define the model class
    protected string $model = Blog::class;
    
    // auto-discovered, use this if you are using custom name
    protected ?string $modelResource = BlogResource::class
}

//resource class
class BlogResource extends BaseResource
{
    // response for index route
    public function toIndexArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'slug' => str($this->title)->slug(),
            'title' => $this->title,
        ];
    }
    // response for show route
    public function toShowArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'slug' => str($this->title)->slug(),
            'title' => $this->title,
            'description' => $this->description,
        ];
    }
    // response for search route
    public function toSearchArray(Request $request): array
    {
        return [
            'key' => $this->id,
            'value' => $this->title,
        ];
    }
}

```

### Request Forms

By default the package will search for a request form that match the model name.
In our example, the package will look for `BlogRequest` under namespace `App\\Http\\Requests`.
If not found, it will use a default request class with no rules to control the received data

```php
use Aldeebhasan\NaiveCrud\Http\Controllers\BaseController;
class BlogController extends BaseController
{
    //required: define the model class
    protected string $model = Blog::class;
    
    // auto-discovered, use this if you are using custom name
    protected ?string $modelRequestForm = BlogRequest::class
}

//Request class
class BlogRequest extends BaseRequest
{
    public function storeRules(): array
    {
        return [
            'title' => 'required|string',
            'description' => 'required|string',
            'image' => 'nullable|string',
        ];
    }

    public function updateRules(): array
    {
        return [
            'title' => 'required|string',
            'description' => 'nullable|string',
            'image' => 'nullable|string',
        ];
    }

    public function toggleRules(): array
    {
        return [
            'active' => 'nullable|boolean',
        ];
    }
}
```

### Filters

We can define a list of filters for each controller to filter the returned data based on some query parameters sent with the api request

```php
use Aldeebhasan\NaiveCrud\Http\Controllers\BaseController;
class BlogController extends BaseController
{
    //required: define the model class
    protected string $model = Blog::class;
    
    protected array $filters = [
        CommonFilter::class
    ];
}

//Request class
class CommonFilter implements FilterUI
{
    public function fields(): array
    {
        return [
            new FilterField(field: 'search', callback: fn ($q, $val) => $q->search($val)),
            new FilterField(field: 'category_id'),
        ];
    }
}
```

When we call the index endpoint ` .../api/blogs` we can pass the filters as follow
` .../api/blogs?filters[search]=title&filters[category_id]=1`
and it will filter the results according to them.
You can group the filter fields as you want in any filter class, and you can use any number of filter class as you want

### Sorters

Similar to `Filters`, we can define some field to order the returned data as we want as follow:

```php
use Aldeebhasan\NaiveCrud\Http\Controllers\BaseController;
class BlogController extends BaseController
{
    //required: define the model class
    protected string $model = Blog::class;
    
    protected array $sorters = [
        CommonSorter::class
    ];
}

//Request class
class CommonSorter implements SortUI
{
    public function fields(): array
    {
        return [
            new SortField(
            field: 'category_id', 
            callback: fn ($q, $direction) {
                $q->orderBy(
                    Category::whereColumn('categories.id','blogs.category_id')->select('categories.priority')),
                     $direction
                   );
            },
            new SortField(field: 'id'),
        ];
    }
}
```

When we call the index endpoint ` .../api/blogs` we can pass the sorters as follow
` .../api/blogs?sorts[id]=desc&filters[tag]=asc`
and it will order the results according to the sorters.
You can group the sorter fields as you want in any sorter class, and you can use any number of sorter class as you want

### Bulk Operations

In general, the bulk operations use the rules defined for store/update, if you want you can override `bulkStoreRules` to add some more rules

Sample request for bulk store:

```
//For bulk store
[POST] .../api/blogs/bulk
[BODY] {
  resources : [
  {
    "name" => "blog 1",
    "description" => "description for blog 1"
  },
  {
    "name" => "blog 2",
    "description" => "description for blog 2"
  }
  ]
}
//For bulk update 
[PUT] .../api/blogs/bulk
[BODY] {
  resources : {
  1 => {
    "name" => "blog 1",
    "description" => "description for blog 1"
  },
  2 => {
    "name" => "blog 2",
    "description" => "description for blog 2",
  },
  }
}

// to bluk delete blog with id = 1 and 2 and 3
[DELETE] .../api/blogs/bulk
[BODY] {
  resources : [1,2,3]
}
```

### Export & Import

Simply, you can import the data from an external file.
First you need to download the sample template file useing `.../api/blogs/import-template`
Then, after you fill some date, you can import it by sending the following request:

```
[POST] .../api/blogs/import
[BODY] {
  file : "path/to/file.csv"
}
```

NOTE: we expect the file to be uploaded and accessible through the provided file path, Also all the import operation are imported chunk by chunk using the queue


On the other hand, to export any data you can use:

```
[POST] .../api/blogs/export
[BODY] {
   'type' => 'excel|csv|html',
   'target' => 'all|page'
}
```

For the case of huge amount of data need to be exported, you can send the export operation to be handled by the queue later.
This operation can be controller by changing `$exportAllShouldQueue` in your controller to be `True.

By doing this, a notification will be send to the current user

**Important: ** when you set exportAllShouldQueue=True, you have to define the notification class that will be used to inform the current user

```php
...
completedJobNotification = ExportDoneNotification::class
...

class  ExportDoneNotification extends Notification{

public function __construct(protected  string $path) {}
}


```

> [!IMPORTANT]
> To enable any model to be imported and exported it should implement the `ExcelUI` Interface
## License

Laravel Naive Crud package is licensed under [The MIT License (MIT)](LICENSE).

## Security contact information

To report a security vulnerability, contact directly to the developer contact email [Here](mailto:aldeeb.91@gmail.com).
