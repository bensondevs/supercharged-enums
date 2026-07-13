<?php

declare(strict_types=1);

namespace BensonDevs\SuperchargedEnums;

use BackedEnum;

/**
 * Wrap a backed enum case or class with runtime EnumExtension helpers.
 *
 * @template T of BackedEnum
 *
 * @param  T|class-string<T>  $target
 * @return ($target is class-string ? SuperchargedEnumType<T> : SuperchargedEnum<T>)
 */
function supercharge(BackedEnum | string $target): SuperchargedEnum | SuperchargedEnumType
{
    if (is_string($target)) {
        return new SuperchargedEnumType($target);
    }

    return new SuperchargedEnum($target);
}
