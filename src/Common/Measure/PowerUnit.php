<?php

declare(strict_types=1);

namespace BensonDevs\SuperchargedEnums\Common\Measure;

use BensonDevs\SuperchargedEnums\Common\Measure\Concerns\ConvertsMeasureUnits;
use BensonDevs\SuperchargedEnums\EnumExtension;

/**
 * Common power units. SI smallest-to-largest, then US mechanical horsepower. Case order defines {@see EnumExtension::default()}.
 *
 * Conversions use watts as the base unit. Backing values are lowercase English slugs.
 */
enum PowerUnit: string
{
    use ConvertsMeasureUnits;
    use EnumExtension;

    case Watt = 'watt';

    case Kilowatt = 'kilowatt';

    case Megawatt = 'megawatt';

    case Horsepower = 'horsepower';

    public function toWatts(int $unit = 1, int $decimalDigits = 2): float
    {
        return $this->fromBaseUnits($this->toBaseUnits($unit), 1, $decimalDigits);
    }

    public function toKilowatts(int $unit = 1, int $decimalDigits = 2): float
    {
        return $this->fromBaseUnits($this->toBaseUnits($unit), 1000, $decimalDigits);
    }

    public function toMegawatts(int $unit = 1, int $decimalDigits = 2): float
    {
        return $this->fromBaseUnits($this->toBaseUnits($unit), 1_000_000, $decimalDigits);
    }

    public function toHorsepower(int $unit = 1, int $decimalDigits = 2): float
    {
        return $this->fromBaseUnits($this->toBaseUnits($unit), 745.69987158227, $decimalDigits);
    }

    private function baseUnitsPerUnit(): float
    {
        return match ($this) {
            self::Watt => 1,
            self::Kilowatt => 1000,
            self::Megawatt => 1_000_000,
            self::Horsepower => 745.69987158227,
        };
    }
}
