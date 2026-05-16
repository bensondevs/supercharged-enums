<?php

declare(strict_types=1);

namespace BensonDevs\SuperchargedEnums\Common\Finance;

use BensonDevs\SuperchargedEnums\EnumExtension;
use InvalidArgumentException;

/**
 * Money and decimal rounding modes. Case order defines {@see EnumExtension::default()}.
 *
 * {@see self::HalfEven} is banker's rounding. Results use IEEE 754 floats (not arbitrary-precision decimals).
 * Backing values are lowercase English slugs.
 */
enum MoneyRoundingMode: string
{
    use EnumExtension;

    case HalfUp = 'half_up';

    case HalfDown = 'half_down';

    case HalfEven = 'half_even';

    case Up = 'up';

    case Down = 'down';

    case TowardZero = 'toward_zero';

    case AwayFromZero = 'away_from_zero';

    public function round(float $value, int $decimalPlaces = 0): float
    {
        if ($decimalPlaces < 0) {
            throw new InvalidArgumentException('decimalPlaces must be zero or greater.');
        }

        return match ($this) {
            self::HalfUp => round($value, $decimalPlaces, PHP_ROUND_HALF_UP),
            self::HalfDown => round($value, $decimalPlaces, PHP_ROUND_HALF_DOWN),
            self::HalfEven => round($value, $decimalPlaces, PHP_ROUND_HALF_EVEN),
            self::Up, self::Down, self::TowardZero, self::AwayFromZero => $this->roundDirectional($value, $decimalPlaces),
        };
    }

    public function roundMoney(float $value, int $decimalPlaces = 2): float
    {
        return $this->round($value, $decimalPlaces);
    }

    private function roundDirectional(float $value, int $decimalPlaces): float
    {
        $scaled = $value * $this->scaleFactor($decimalPlaces);

        $rounded = match ($this) {
            self::Up => ceil($scaled),
            self::Down => floor($scaled),
            self::TowardZero => $scaled >= 0 ? floor($scaled) : ceil($scaled),
            self::AwayFromZero => $scaled >= 0 ? ceil($scaled) : floor($scaled),
            default => $scaled,
        };

        return $rounded / $this->scaleFactor($decimalPlaces);
    }

    private function scaleFactor(int $decimalPlaces): float
    {
        return 10 ** $decimalPlaces;
    }
}
