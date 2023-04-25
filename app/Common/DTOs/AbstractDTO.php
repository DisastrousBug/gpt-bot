<?php

namespace App\Common\DTOs;

use Carbon\Carbon;
use JsonSerializable;
use Illuminate\Support\Str;
use Illuminate\Contracts\Support\Arrayable;

class AbstractDTO implements DTO, JsonSerializable, Arrayable
{
    public function toArray(): array
    {
        return collect(get_object_vars($this))->mapWithKeys(function ($value, $key) {
            if ($value instanceof AbstractDTO) {
                $value = $value->toArray();
            } elseif (is_array($value)) {
                $value = collect($value)->map(function ($value) {
                    if ($value instanceof AbstractDTO) {
                        return $value->toArray();
                    }

                    return $value;
                });
            } elseif ($value instanceof Carbon) {
                $value = $value->toISOString(true);
            }

            return [Str::snake($key) => $value];
        })->all();
    }

    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }
}
