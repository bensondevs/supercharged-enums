<?php

declare(strict_types=1);

namespace BensonDevs\SuperchargedEnums\Common\Measure;

use BensonDevs\SuperchargedEnums\Common\Measure\Concerns\ConvertsMeasureUnits;
use BensonDevs\SuperchargedEnums\EnumExtension;

/**
 * Common volume units. Metric first, then **US liquid** customary units only. Case order defines {@see EnumExtension::default()}.
 *
 * Backing values are lowercase English slugs.
 */
enum VolumeUnit: string
{
    use ConvertsMeasureUnits;
    use EnumExtension;

    case Milliliter = 'milliliter';

    case Liter = 'liter';

    case UsLiquidGallon = 'us_liquid_gallon';

    case UsLiquidQuart = 'us_liquid_quart';

    case UsLiquidPint = 'us_liquid_pint';

    case UsCup = 'us_cup';

    case UsFluidOunce = 'us_fluid_ounce';

    public function toMilliliters(int $unit = 1, int $decimalDigits = 2): float
    {
        return $this->fromBaseUnits($this->toBaseUnits($unit), 1, $decimalDigits);
    }

    public function toLiters(int $unit = 1, int $decimalDigits = 2): float
    {
        return $this->fromBaseUnits($this->toBaseUnits($unit), 1000, $decimalDigits);
    }

    public function toUsLiquidGallons(int $unit = 1, int $decimalDigits = 2): float
    {
        return $this->fromBaseUnits($this->toBaseUnits($unit), 3785.411784, $decimalDigits);
    }

    public function toUsLiquidQuarts(int $unit = 1, int $decimalDigits = 2): float
    {
        return $this->fromBaseUnits($this->toBaseUnits($unit), 946.352946, $decimalDigits);
    }

    public function toUsLiquidPints(int $unit = 1, int $decimalDigits = 2): float
    {
        return $this->fromBaseUnits($this->toBaseUnits($unit), 473.176473, $decimalDigits);
    }

    public function toUsCups(int $unit = 1, int $decimalDigits = 2): float
    {
        return $this->fromBaseUnits($this->toBaseUnits($unit), 236.5882365, $decimalDigits);
    }

    public function toUsFluidOunces(int $unit = 1, int $decimalDigits = 2): float
    {
        return $this->fromBaseUnits($this->toBaseUnits($unit), 29.5735295625, $decimalDigits);
    }

    private function baseUnitsPerUnit(): float
    {
        return match ($this) {
            self::Milliliter => 1,
            self::Liter => 1000,
            self::UsLiquidGallon => 3785.411784,
            self::UsLiquidQuart => 946.352946,
            self::UsLiquidPint => 473.176473,
            self::UsCup => 236.5882365,
            self::UsFluidOunce => 29.5735295625,
        };
    }
}
