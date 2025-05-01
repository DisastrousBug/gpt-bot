<?php

namespace App\Common\Factories;

use App\Common\DTOs\AbstractDTO;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

interface Factory
{
    public static function fromRequest(Request $request): AbstractDTO;

    public static function fromRequestWithFiles(FormRequest $request): AbstractDTO;

    public static function fromRequestValidated(FormRequest $request): AbstractDTO;

    public static function fromRequestValidatedWithFiles(FormRequest $request): AbstractDTO;

    /**
     * @param  array<string, mixed>  $array
     */
    public static function fromArray(array $array): AbstractDTO;

    /**
     * @param  Collection<string, mixed>  $collection
     */
    public static function fromCollection(Collection $collection): AbstractDTO;
}
