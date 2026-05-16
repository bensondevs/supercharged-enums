<?php

declare(strict_types=1);

namespace BensonDevs\SuperchargedEnums\Common\Measure;

use BensonDevs\SuperchargedEnums\Common\Measure\Concerns\ConvertsMeasureUnits;
use BensonDevs\SuperchargedEnums\EnumExtension;

/**
 * Common area units. Metric smallest-to-largest, then imperial smallest-to-largest. Case order defines {@see EnumExtension::default()}.
 *
 * Conversions use square millimeters as the base unit. Backing values are lowercase English slugs.
 */
enum AreaUnit: string
{
    use ConvertsMeasureUnits;
    use EnumExtension;

    case SqMm = 'sq_mm';

    case SqCm = 'sq_cm';

    case SqM = 'sq_m';

    case SqKm = 'sq_km';

    case Hectare = 'hectare';

    case Acre = 'acre';

    case SqInch = 'sq_inch';

    case SqFoot = 'sq_foot';

    case SqYard = 'sq_yard';

    case SqMile = 'sq_mile';

    public function toSqMillimeters(int $unit = 1, int $decimalDigits = 2): float
    {
        return $this->fromBaseUnits($this->toBaseUnits($unit), 1, $decimalDigits);
    }

    public function toSqCentimeters(int $unit = 1, int $decimalDigits = 2): float
    {
        return $this->fromBaseUnits($this->toBaseUnits($unit), 100, $decimalDigits);
    }

    public function toSqMeters(int $unit = 1, int $decimalDigits = 2): float
    {
        return $this->fromBaseUnits($this->toBaseUnits($unit), 1_000_000, $decimalDigits);
    }

    public function toSqKilometers(int $unit = 1, int $decimalDigits = 2): float
    {
        return $this->fromBaseUnits($this->toBaseUnits($unit), 1_000_000_000_000, $decimalDigits);
    }

    public function toHectares(int $unit = 1, int $decimalDigits = 2): float
    {
        return $this->fromBaseUnits($this->toBaseUnits($unit), 10_000_000_000, $decimalDigits);
    }

    public function toAcres(int $unit = 1, int $decimalDigits = 2): float
    {
        return $this->fromBaseUnits($this->toBaseUnits($unit), 4_046_856_422.4, $decimalDigits);
    }

    public function toSqInches(int $unit = 1, int $decimalDigits = 2): float
    {
        return $this->fromBaseUnits($this->toBaseUnits($unit), 645.16, $decimalDigits);
    }

    public function toSqFeet(int $unit = 1, int $decimalDigits = 2): float
    {
        return $this->fromBaseUnits($this->toBaseUnits($unit), 92_903.04, $decimalDigits);
    }

    public function toSqYards(int $unit = 1, int $decimalDigits = 2): float
    {
        return $this->fromBaseUnits($this->toBaseUnits($unit), 836_127.36, $decimalDigits);
    }

    public function toSqMiles(int $unit = 1, int $decimalDigits = 2): float
    {
        return $this->fromBaseUnits($this->toBaseUnits($unit), 2_589_988_110_336, $decimalDigits);
    }

    private function baseUnitsPerUnit(): float
    {
        return match ($this) {
            self::SqMm => 1,
            self::SqCm => 100,
            self::SqM => 1_000_000,
            self::SqKm => 1_000_000_000_000,
            self::Hectare => 10_000_000_000,
            self::Acre => 4_046_856_422.4,
            self::SqInch => 645.16,
            self::SqFoot => 92_903.04,
            self::SqYard => 836_127.36,
            self::SqMile => 2_589_988_110_336,
        };
    }
}
