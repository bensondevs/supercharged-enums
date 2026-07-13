<?php

declare(strict_types=1);

namespace BensonDevs\SuperchargedEnums;

use BensonDevs\SuperchargedEnums\Concerns\EnumCaseListing;
use BensonDevs\SuperchargedEnums\Concerns\EnumComparisons;
use BensonDevs\SuperchargedEnums\Concerns\EnumLookup;
use BensonDevs\SuperchargedEnums\Concerns\EnumNaming;
use BensonDevs\SuperchargedEnums\Concerns\EnumSelectMaps;
use BensonDevs\SuperchargedEnums\Support\BackedEnumCore;

trait EnumExtension
{
    use EnumCaseListing;
    use EnumComparisons;
    use EnumLookup;
    use EnumNaming;
    use EnumSelectMaps;

    public static function default(): static
    {
        return self::cases()[0];
    }

    public static function getDefault(): static
    {
        return self::default();
    }

    public static function random(): static
    {
        /** @var static */
        return BackedEnumCore::random(static::class);
    }
}
