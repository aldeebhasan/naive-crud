<?php

namespace Aldeebhasan\NaiveCrud\Test\Sample\App\Models;

use Aldeebhasan\NaiveCrud\Contracts\ExcelUI;
use Aldeebhasan\NaiveCrud\Test\Sample\Database\Factories\BlogFactory;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Blog extends Model implements ExcelUI
{
    use SoftDeletes, HasFactory;

    protected $fillable = ['title', 'description', 'user_id', 'image'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public static function headerFields(): array
    {
        return ['title', 'description', 'image'];
    }

    public static function formatImportItem(array $row, Authenticatable $user): array
    {
        return $row + ['user_id' => $user->getAuthIdentifier()];
    }

    public function formatExportItem(): array
    {
        return $this->toArray();
    }

    protected static function newFactory()
    {
        return new BlogFactory();
    }
}
