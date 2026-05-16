<?php

declare(strict_types=1);

namespace BensonDevs\SuperchargedEnums\Common\Platform;

use BensonDevs\SuperchargedEnums\EnumExtension;

/**
 * Common operating system families. Case order defines {@see EnumExtension::default()}.
 *
 * Backing values are lowercase English slugs.
 */
enum OperatingSystemFamily: string
{
    use EnumExtension;

    case Linux = 'linux';

    case Windows = 'windows';

    case Macos = 'macos';

    case Bsd = 'bsd';
}
