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

    /**
     * @return array<string, string>
     */
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
     * @return array<int, static>
     */
    public static function filteredCases(): array
    {
        /** @var array<int, static> */
        return BackedEnumSelectMaps::filteredCases(static::class);
    }

    /**
     * Filtered enum cases as an array. Alias for {@see filteredCases()}.
     *
     * @return array<int, static>
     */
    public static function all(): array
    {
        /** @var array<int, static> */
        return BackedEnumSelectMaps::all(static::class);
    }

    /**
     * Filtered enum cases as a Laravel Collection.
     *
     * @return \Illuminate\Support\Collection<int, static>
     */
    public static function collect(): object
    {
        /** @var \Illuminate\Support\Collection<int, static> */
        return BackedEnumSelectMaps::collect(static::class);
    }
}
