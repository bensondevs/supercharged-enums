<?php

declare(strict_types=1);

namespace BensonDevs\SuperchargedEnums\Common\Calendar;

use BensonDevs\SuperchargedEnums\EnumExtension;

/**
 * Gregorian calendar months (January through December). Case order defines {@see EnumExtension::default()}.
 *
 * Backing values are lowercase English slugs.
 */
enum Month: string
{
    use EnumExtension;

    case January = 'january';

    case February = 'february';

    case March = 'march';

    case April = 'april';

    case May = 'may';

    case June = 'june';

    case July = 'july';

    case August = 'august';

    case September = 'september';

    case October = 'october';

    case November = 'november';

    case December = 'december';
}
