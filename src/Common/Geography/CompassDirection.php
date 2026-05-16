<?php

declare(strict_types=1);

namespace BensonDevs\SuperchargedEnums\Common\Geography;

use BensonDevs\SuperchargedEnums\EnumExtension;

/**
 * Eight compass directions clockwise from north. Case order defines {@see EnumExtension::default()}.
 *
 * Backing values are lowercase English slugs (single- or two-letter abbreviations).
 */
enum CompassDirection: string
{
    use EnumExtension;

    case North = 'n';

    case NorthEast = 'ne';

    case East = 'e';

    case SouthEast = 'se';

    case South = 's';

    case SouthWest = 'sw';

    case West = 'w';

    case NorthWest = 'nw';
}
