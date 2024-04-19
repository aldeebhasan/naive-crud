<?php

namespace Aldeebhasan\NaiveCrud\Http\Resources;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

class BaseResource extends JsonResource
{
    protected ?Authenticatable $user;

    protected bool $forShow = false;

    public function withUser(?Authenticatable $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function forShow(bool $forShow = true): self
    {
        $this->forShow = $forShow;

        return $this;
    }

    public static function makeCustom($parameters, $user = null, $forShow = true): self
    {
        return self::make($parameters)->forShow($forShow)->withUser($user);

    }

    public static function collectionCustom($resource, $user = null, $forShow = false): Collection
    {
        return collect($resource)->map(
            fn ($item) => self::makeCustom($item, $forShow, $user)
        )->values();
    }
}
