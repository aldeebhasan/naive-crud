<?php

namespace Aldeebhasan\NaiveCrud\Test\Sample\App\Models;

use Aldeebhasan\NaiveCrud\Test\Sample\Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'name', 'email', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function blogs(): HasMany
    {
        return $this->hasMany(Blog::class);
    }

    protected static function newFactory()
    {
        return new UserFactory();
    }
}
