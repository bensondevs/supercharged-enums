<?php

declare(strict_types=1);

namespace BensonDevs\SuperchargedEnums\Common\Measure;

use BensonDevs\SuperchargedEnums\Common\Measure\Concerns\ConvertsMeasureUnits;
use BensonDevs\SuperchargedEnums\EnumExtension;

/**
 * Common length units. Metric smallest-to-largest, then imperial smallest-to-largest. Case order defines {@see EnumExtension::default()}.
 *
 * Inch, foot, yard, and mile use international conversion factors to millimeters.
 * Backing values are lowercase slugs (metric uses short SI-style tokens mm, cm, m, km).
 */
enum LengthUnit: string
{
    use ConvertsMeasureUnits;
    use EnumExtension;

    case Millimeter = 'mm';

    case Centimeter = 'cm';

    case Meter = 'm';

    case Kilometer = 'km';

    case Inch = 'inch';

    case Foot = 'foot';

    case Yard = 'yard';

    case Mile = 'mile';

    public function toMillimeters(int $unit = 1, int $decimalDigits = 2): float
    {
        return $this->fromBaseUnits($this->toBaseUnits($unit), 1, $decimalDigits);
    }

    public function toCentimeters(int $unit = 1, int $decimalDigits = 2): float
    {
        return $this->fromBaseUnits($this->toBaseUnits($unit), 10, $decimalDigits);
    }

    public function toMeters(int $unit = 1, int $decimalDigits = 2): float
    {
        return $this->fromBaseUnits($this->toBaseUnits($unit), 1000, $decimalDigits);
    }

    public function toKilometers(int $unit = 1, int $decimalDigits = 2): float
    {
        return $this->fromBaseUnits($this->toBaseUnits($unit), 1_000_000, $decimalDigits);
    }

    public function toInches(int $unit = 1, int $decimalDigits = 2): float
    {
        return $this->fromBaseUnits($this->toBaseUnits($unit), 25.4, $decimalDigits);
    }

    public function toFeet(int $unit = 1, int $decimalDigits = 2): float
    {
        return $this->fromBaseUnits($this->toBaseUnits($unit), 304.8, $decimalDigits);
    }

    public function toYards(int $unit = 1, int $decimalDigits = 2): float
    {
        return $this->fromBaseUnits($this->toBaseUnits($unit), 914.4, $decimalDigits);
    }

    public function toMiles(int $unit = 1, int $decimalDigits = 2): float
    {
        return $this->fromBaseUnits($this->toBaseUnits($unit), 1_609_344, $decimalDigits);
    }

    private function baseUnitsPerUnit(): float
    {
        return match ($this) {
            self::Millimeter => 1,
            self::Centimeter => 10,
            self::Meter => 1000,
            self::Kilometer => 1_000_000,
            self::Inch => 25.4,
            self::Foot => 304.8,
            self::Yard => 914.4,
            self::Mile => 1_609_344,
        };
    }
}
