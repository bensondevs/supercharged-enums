<?php

declare(strict_types=1);

namespace BensonDevs\SuperchargedEnums\Common\Calendar;

use BensonDevs\SuperchargedEnums\EnumExtension;

/**
 * Weekdays in ISO 8601 order (Monday first). Case order defines {@see EnumExtension::default()}.
 *
 * Backing values are lowercase English slugs.
 */
enum DayOfWeek: string
{
    use EnumExtension;

    case Monday = 'monday';

    case Tuesday = 'tuesday';

    case Wednesday = 'wednesday';

    case Thursday = 'thursday';

    case Friday = 'friday';

    case Saturday = 'saturday';

    case Sunday = 'sunday';
}
