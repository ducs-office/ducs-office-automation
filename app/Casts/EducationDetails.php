<?php

namespace App\Casts;

use App\Types\EducationInfo;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class EducationDetails implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes)
    {
        $collection = collect(json_decode($value, true));

        return $collection->map(function ($value) {
            return new EducationInfo($value);
        })->toArray();
    }

    public function set($model, string $key, $value, array $attributes)
    {
        return collect($value)->map(function ($info) {
            if ($info instanceof EducationInfo) {
                return $info->toArray();
            }

            return $info;
        })->toJson();
    }
}
