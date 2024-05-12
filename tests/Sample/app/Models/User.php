<?php

namespace Aldeebhasan\NaiveCrud\Test\Sample\App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
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
}
