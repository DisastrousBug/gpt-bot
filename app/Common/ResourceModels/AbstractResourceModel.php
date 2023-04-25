<?php

namespace App\Common\ResourceModels;

use Carbon\Carbon;
use JsonSerializable;
use Illuminate\Support\Str;
use Illuminate\Contracts\Support\Arrayable;

abstract class AbstractResourceModel implements JsonSerializable, Arrayable
{
    protected const SKIP = [];

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    public function toArray(): array
    {
        $propMap = [];

        foreach (get_object_vars($this) as $propName => $prop) {
            if (! in_array($propName, static::SKIP, true)) {
                $propMap[$propName] = Str::snake($propName);
            }
        }

        $result = [];

        foreach ($propMap as $originName => $transformName) {
            $origin = $this->{$originName};

            if ($origin instanceof Carbon) {
                $origin = $origin->toISOString(true);
            } elseif ($origin instanceof Arrayable) {
                $origin = $origin->toArray();
            }

            $result[$transformName] = $origin;
        }

        return $result;
    }
}
