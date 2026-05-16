<?php

declare(strict_types=1);

namespace BensonDevs\SuperchargedEnums\Common\Calendar;

use BensonDevs\SuperchargedEnums\EnumExtension;

/**
 * Human-facing date display conventions (not storage or wire formats). Case order defines {@see EnumExtension::default()}.
 *
 * {@see self::EuropeanNumeric} and {@see self::BritishNumeric} share {@see format()} pattern `d/m/Y`; slugs express
 * intent and locale wiring, not a unique pattern per case.
 * {@see self::Japanese} uses a best-effort PHP pattern; production Japanese UI may prefer {@see \IntlDateFormatter}
 * with `ja_JP` while storing this case slug as the user preference.
 *
 * Backing values are lowercase English slugs.
 */
enum DateDisplayFormat: string
{
    use EnumExtension;

    case IsoDisplay = 'iso_display';

    case UsNumeric = 'us_numeric';

    case UsLong = 'us_long';

    case EuropeanNumeric = 'european_numeric';

    case EuropeanDots = 'european_dots';

    case BritishNumeric = 'british_numeric';

    case ShortMonth = 'short_month';

    case LongMonth = 'long_month';

    case Japanese = 'japanese';

    public function format(): string
    {
        return match ($this) {
            self::IsoDisplay => 'Y-m-d',
            self::UsNumeric => 'm/d/Y',
            self::UsLong => 'F j, Y',
            self::EuropeanNumeric, self::BritishNumeric => 'd/m/Y',
            self::EuropeanDots => 'd.m.Y',
            self::ShortMonth => 'd-M-Y',
            self::LongMonth => 'j F Y',
            self::Japanese => 'Y年n月j日',
        };
    }
}
