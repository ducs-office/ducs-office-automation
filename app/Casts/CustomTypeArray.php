<?php

namespace App\Casts;

use App\Exceptions\InvalidTypeValue;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class CustomTypeArray implements CastsAttributes
{
    protected $type;
    protected $seperator;

    public function __construct($type, $seperator = '|')
    {
        $this->type = $type;
        $this->seperator = $seperator;
    }

    public function get($model, string $key, $value, array $attributes)
    {
        return array_map(function ($item) {
            return $this->parse($item);
        }, explode($this->seperator, $value));
    }

    public function set($model, string $key, $value, array $attributes)
    {
        if (! is_array($value)) {
            return implode($this->seperator, [$this->stringify($value)]);
        }

        return implode($this->seperator, array_map(function ($item) {
            return $this->stringify($item);
        }, $value));
    }

    private function parse($value)
    {
        return new $this->type($value);
    }

    private function stringify($value)
    {
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
