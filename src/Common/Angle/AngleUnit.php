<?php

declare(strict_types=1);

namespace BensonDevs\SuperchargedEnums\Common\Angle;

use BensonDevs\SuperchargedEnums\EnumExtension;

/**
 * Common plane angle units. Case order defines {@see EnumExtension::default()}.
 *
 * Backing values are lowercase English slugs.
 */
enum AngleUnit: string
{
    use EnumExtension;

    case Degree = 'degree';

    case Radian = 'radian';

    case Gradian = 'gradian';
}
