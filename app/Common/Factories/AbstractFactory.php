<?php

namespace App\Common\Factories;

use App\Common\DTOs\AbstractDTO;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * @template T of AbstractDTO
 */
abstract class AbstractFactory implements Factory
{
    public static function fromRequest(Request $request): AbstractDTO
    {
        return static::fromCollection(collect($request->all()));
    }

    public static function fromRequestWithFiles(Request $request): AbstractDTO
    {
        return static::fromCollection(collect(array_merge($request->all(), $request->allFiles())));
    }

    public static function fromRequestValidated(FormRequest $request): AbstractDTO
    {
        return static::fromCollection(collect($request->validated()));
    }

    public static function fromRequestValidatedWithFiles(FormRequest $request): AbstractDTO
    {
        return static::fromCollection(collect(array_merge($request->validated(), $request->allFiles())));
    }

    /**
     * @param  array<string, mixed>  $array
     */
    public static function fromArray(array $array): AbstractDTO
    {
        return static::fromCollection(collect($array));
    }

    /**
     * @param  Collection<string, mixed>  $collection
     */
    public static function fromCollection(Collection $collection): AbstractDTO
    {
        $dto = static::getDTO();

        return self::reader($dto, $collection);
    }

    /**
     * @param  Collection<string,mixed>|array<string,mixed>  $collection
     */
    protected static function reader(AbstractDTO $class, Collection|array $collection): AbstractDTO
    {
        foreach ($collection as $key => $value) {
            $key = Str::studly($key);
            $lcFirstKey = lcfirst($key);

            if (method_exists(static::class, 'set'.$key)) {
                $value = static::{'set'.$key}($value);
            }

            if (property_exists($class, $lcFirstKey)) {
                $class->{$lcFirstKey} = $value;
            }
        }

        return $class;
    }

    abstract public static function getDTO(): AbstractDTO;
}
