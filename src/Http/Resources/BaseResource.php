<?php

namespace Aldeebhasan\NaiveCrud\Http\Resources;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

class BaseResource extends JsonResource
{
    protected ?Authenticatable $user;

    protected bool $forShow = false;

    protected bool $forSearch = false;

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

    public function forSearch(bool $forSearch = true): self
    {
        $this->forSearch = $forSearch;

        return $this;
    }

    public static function makeCustom($parameters, $user = null, $forShow = true, $forSearch = false): self
    {
        return self::make($parameters)->forShow($forShow)->forSearch($forSearch)->withUser($user);

    }

    public static function collectionCustom($resource, $user = null, $forShow = false, $forSearch = false): Collection
    {
        return collect($resource)->map(
            fn ($item) => self::makeCustom($item, $user, $forShow, $forSearch)
        )->values();
    }

    public function toArray(Request $request)
    {
        if ($this->forShow) {
            return $this->toShowArray($request);
        }
        if ($this->forSearch) {
            return $this->toSearchArray($request);
        }

        return $this->toIndexArray($request);
    }

    public function toIndexArray(Request $request): array
    {
        return parent::toArray($request);
    }

    public function toShowArray(Request $request): array
    {
        return $this->toIndexArray($request);
    }

    public function toSearchArray(Request $request): array
    {
        return $this->toIndexArray($request);
    }
}
