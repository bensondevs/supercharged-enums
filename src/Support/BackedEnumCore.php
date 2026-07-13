<?php

declare(strict_types=1);

namespace BensonDevs\SuperchargedEnums\Support;

use BackedEnum;

final class BackedEnumCore
{
    /**
     * @template T of BackedEnum
     *
     * @param  class-string<T>  $enumClass
     * @return T
     */
    public static function default(string $enumClass): BackedEnum
    {
        if (method_exists($enumClass, 'default')) {
            return $enumClass::default();
        }

        return $enumClass::cases()[0];
    }

    /**
     * @template T of BackedEnum
     *
     * @param  class-string<T>  $enumClass
     * @return T
     */
    public static function getDefault(string $enumClass): BackedEnum
    {
        return self::default($enumClass);
    }

    /**
     * @template T of BackedEnum
     *
     * @param  class-string<T>  $enumClass
     * @return T
     */
    public static function random(string $enumClass): BackedEnum
    {
        $cases = $enumClass::cases();

        return $cases[array_rand($cases)];
    }
}
