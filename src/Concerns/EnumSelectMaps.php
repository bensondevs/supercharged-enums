<?php

declare(strict_types=1);

namespace BensonDevs\SuperchargedEnums\Concerns;

use BensonDevs\SuperchargedEnums\Support\BackedEnumSelectMaps;

trait EnumSelectMaps
{
    /**
     * @return array<string, string>
     */
    public static function options(): array
    {
        return BackedEnumSelectMaps::options(static::class);
    }

    public static function asSelectOptions(): array
    {
        return self::options();
    }

    /**
     * @return array<string, string>
     */
    public static function asSelectDescriptions(): array
    {
        return BackedEnumSelectMaps::asSelectDescriptions(static::class);
    }

    /**
     * Cases included in select maps. When {@see selectables()} exists it wins over {@see unselectables()}.
     *
     * @return array<static>
     */
    public static function filteredCases(): array
    {
        return BackedEnumSelectMaps::filteredCases(static::class);
    }
}
