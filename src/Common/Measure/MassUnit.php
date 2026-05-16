<?php

declare(strict_types=1);

namespace BensonDevs\SuperchargedEnums\Common\Measure;

use BensonDevs\SuperchargedEnums\Common\Measure\Concerns\ConvertsMeasureUnits;
use BensonDevs\SuperchargedEnums\EnumExtension;

/**
 * Common mass units. Metric smallest-to-largest, then US customary. Case order defines {@see EnumExtension::default()}.
 *
 * {@see self::Tonne} is the metric ton (1000 kg). Backing values are lowercase English slugs.
 */
enum MassUnit: string
{
    use ConvertsMeasureUnits;
    use EnumExtension;

    case Milligram = 'milligram';

    case Gram = 'gram';

    case Kilogram = 'kilogram';

    case Tonne = 'tonne';

    case Ounce = 'ounce';

    case Pound = 'pound';

    public function toMilligrams(int $unit = 1, int $decimalDigits = 2): float
    {
        return $this->fromBaseUnits($this->toBaseUnits($unit), 1, $decimalDigits);
    }

    public function toGrams(int $unit = 1, int $decimalDigits = 2): float
    {
        return $this->fromBaseUnits($this->toBaseUnits($unit), 1000, $decimalDigits);
    }

    public function toKilograms(int $unit = 1, int $decimalDigits = 2): float
    {
        return $this->fromBaseUnits($this->toBaseUnits($unit), 1_000_000, $decimalDigits);
    }

    public function toTonnes(int $unit = 1, int $decimalDigits = 2): float
    {
        return $this->fromBaseUnits($this->toBaseUnits($unit), 1_000_000_000, $decimalDigits);
    }

    public function toOunces(int $unit = 1, int $decimalDigits = 2): float
    {
        return $this->fromBaseUnits($this->toBaseUnits($unit), 28349.523125, $decimalDigits);
    }

    public function toPounds(int $unit = 1, int $decimalDigits = 2): float
    {
        return $this->fromBaseUnits($this->toBaseUnits($unit), 453592.37, $decimalDigits);
    }

    private function baseUnitsPerUnit(): float
    {
        return match ($this) {
            self::Milligram => 1,
            self::Gram => 1000,
            self::Kilogram => 1_000_000,
            self::Tonne => 1_000_000_000,
            self::Ounce => 28349.523125,
            self::Pound => 453592.37,
        };
    }
}
