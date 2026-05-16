<?php

declare(strict_types=1);

namespace BensonDevs\SuperchargedEnums\Common\Device;

use BensonDevs\SuperchargedEnums\EnumExtension;

/**
 * Common client device categories for analytics, responsive UI, and API metadata. Case order defines {@see EnumExtension::default()}.
 *
 * {@see self::Desktop} includes laptops. Not vendor or model names. Backing values are lowercase English slugs.
 */
enum DeviceType: string
{
    use EnumExtension;

    case Mobile = 'mobile';

    case Tablet = 'tablet';

    case Desktop = 'desktop';

    case Tv = 'tv';

    case Wearable = 'wearable';

    case Console = 'console';

    case Embedded = 'embedded';

    case Unknown = 'unknown';
}
