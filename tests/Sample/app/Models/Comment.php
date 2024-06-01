<?php

namespace Aldeebhasan\NaiveCrud\Test\Sample\App\Models;

use Aldeebhasan\NaiveCrud\Contracts\ExcelUI;
use Aldeebhasan\NaiveCrud\Test\Sample\Database\Factories\CommentFactory;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model implements ExcelUI
{
    use SoftDeletes, HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['content', 'user_id', 'blog_id', 'active'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function blog(): BelongsTo
    {
        return $this->belongsTo(Blog::class);
    }

    protected static function newFactory()
    {
        return new CommentFactory();
    }

    public static function headerFields(): array
    {
        return ['content', 'user_id', 'blog_id', 'active'];
    }

    public function formatExportItem(): array
    {
        return $this->toArray();
    }

    public static function formatImportItem(array $row, Authenticatable $user): array
    {
        return $row;
    }
}
