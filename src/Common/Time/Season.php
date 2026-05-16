<?php

declare(strict_types=1);

namespace BensonDevs\SuperchargedEnums\Common\Time;

use BensonDevs\SuperchargedEnums\EnumExtension;

/**
 * Meteorological seasons in declaration order. Case order defines {@see EnumExtension::default()}.
 *
 * Backing values are lowercase English slugs ({@see self::Autumn} uses `autumn`, not `fall`).
 */
enum Season: string
{
    use EnumExtension;

    case Spring = 'spring';

    case Summer = 'summer';

    case Autumn = 'autumn';

    case Winter = 'winter';
}
