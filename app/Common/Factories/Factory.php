<?php

namespace App\Common\Factories;

use Illuminate\Http\Request;
use App\Common\DTOs\AbstractDTO;
use Illuminate\Support\Collection;
use Illuminate\Foundation\Http\FormRequest;

interface Factory
{
    public static function fromRequest(Request $request): AbstractDTO;

    public static function fromRequestWithFiles(FormRequest $request): AbstractDTO;

    public static function fromRequestValidated(FormRequest $request): AbstractDTO;

    public static function fromRequestValidatedWithFiles(FormRequest $request): AbstractDTO;

    public static function fromArray(array $array): AbstractDTO;

    public static function fromCollection(Collection $collection): AbstractDTO;
}
