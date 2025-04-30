<?php

namespace App\Common\ResourceModels;

use BackedEnum;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Str;
use JsonSerializable;
use Symfony\Component\Mime\Address;

/**
 * @template TKey of array-key
 * @template TValue
 *
 * @implements Arrayable<TKey, TValue>
 */
abstract class AbstractResourceModel implements Arrayable, JsonSerializable
{
    protected const array SKIP = [];

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    /**
     * @return array<string, mixed>
     */
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

            if ($origin instanceof Arrayable) {
                $origin = $origin->toArray();
            } elseif ($origin instanceof BackedEnum) {
                $origin = $origin->value;
            } elseif ($origin instanceof Carbon) {
                $origin = $origin->toISOString(true);
            } elseif ($origin instanceof Address) {
                $origin = $origin->getAddress();
            }

            $result[$transformName] = $origin;
        }

        return $result;
    }
}
