<?php

namespace Aldeebhasan\NaiveCrud\Test\Sample\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Blog extends Model
{
    use SoftDeletes;

    protected $fillable = ['title', 'description', 'user_id', 'image'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public static function importFields(): array
    {
        return ['title', 'description', 'image'];
    }

    public static function formatImportItem(array $row, Model $user): array
    {
        return $row + ['user_id' => $user->id];
    }
}
