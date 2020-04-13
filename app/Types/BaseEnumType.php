<?php

namespace App\Types;

use App\Exceptions\InvalidTypeValue;
use ReflectionClass;

abstract class BaseEnumType
{
    protected $value;

    private static $cachedConstants;

    private static function getConstants(): array
    {
        if (self::$cachedConstants == null) {
            self::$cachedConstants = [];
        }

        $calledClass = get_called_class();

        if (! array_key_exists($calledClass, self::$cachedConstants)) {
            $reflect = new ReflectionClass($calledClass);
            self::$cachedConstants[$calledClass] = $reflect->getConstants();
        }

        return self::$cachedConstants[$calledClass];
    }

    public static function values(): array
    {
        return array_values(self::getConstants());
    }

    public static function all(): array
    {
        return array_map(function ($value) {
            return new self($value);
        }, self::values());
    }

    public static function isValid($value): bool
    {
        return in_array($value, self::values());
    }

    public function __construct($value)
    {
        if (! self::isValid($value)) {
            throw new InvalidTypeValue($value, get_called_class());
        }

        $this->value = $value ?? $this->defaultValue;
    }

    public function equals($value)
    {
        return $this->value === $value;
    }

    public function __toString()
    {
        return $this->value;
    }
}
