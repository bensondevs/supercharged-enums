<?php

declare(strict_types=1);

namespace BensonDevs\SuperchargedEnums\Common\Calendar;

use BensonDevs\SuperchargedEnums\EnumExtension;

/**
 * Gregorian calendar quarters. Case order defines {@see EnumExtension::default()}.
 *
 * Backing values are lowercase slugs q1 through q4.
 */
enum Quarter: string
{
    use EnumExtension;

    case Q1 = 'q1';

    case Q2 = 'q2';

    case Q3 = 'q3';

    case Q4 = 'q4';
}
