<?php

declare(strict_types=1);

namespace BensonDevs\SuperchargedEnums\Concerns;

use BensonDevs\SuperchargedEnums\Support\BackedEnumCaseListing;

trait EnumCaseListing
{
    public static function names(): array
    {
        return BackedEnumCaseListing::names(static::class);
    }

    public static function values(): array
    {
        return BackedEnumCaseListing::values(static::class);
    }
}
