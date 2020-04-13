<?php

namespace App\Casts;

use App\Exceptions\InvalidTypeValue;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class CustomType implements CastsAttributes
{
    protected $type;

    public function __construct($type)
    {
        $this->type = $type;
    }

    public function get($model, string $key, $value, array $attributes)
    {
        return $value !== null ? new $this->type($value) : null;
    }

    public function set($model, string $key, $value, array $attributes)
    {
        if (is_null($value)) {
            return null;
        }

        if (! is_string($value)) {
            return (string) $value;
        }

        if (
            ! method_exists($this->type, 'isValid') ||
            ! $this->type::isValid($value)
        ) {
            throw new InvalidTypeValue($value, $this->type);
        }

        return $value;
    }
}
