<?php

declare(strict_types=1);

namespace BensonDevs\SuperchargedEnums\Concerns;

use BensonDevs\SuperchargedEnums\Support\BackedEnumCaseListing;

trait EnumCaseListing
{
    /**
     * @return array<int, string>
     */
    public static function names(): array
    {
        return BackedEnumCaseListing::names(static::class);
    }

    /**
     * @return array<int, string|int>
     */
    public static function values(): array
    {
        return BackedEnumCaseListing::values(static::class);
    }
}
