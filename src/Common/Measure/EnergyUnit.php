<?php

declare(strict_types=1);

namespace BensonDevs\SuperchargedEnums\Common\Measure;

use BensonDevs\SuperchargedEnums\Common\Measure\Concerns\ConvertsMeasureUnits;
use BensonDevs\SuperchargedEnums\EnumExtension;

/**
 * Common energy units. SI smallest-to-largest, then US customary. Case order defines {@see EnumExtension::default()}.
 *
 * Conversions use joules as the base unit. {@see self::Calorie} is the thermochemical calorie.
 * Backing values are lowercase English slugs.
 */
enum EnergyUnit: string
{
    use ConvertsMeasureUnits;
    use EnumExtension;

    case Joule = 'joule';

    case Kilojoule = 'kilojoule';

    case Megajoule = 'megajoule';

    case KilowattHour = 'kilowatt_hour';

    case Calorie = 'calorie';

    case Btu = 'btu';

    public function toJoules(int $unit = 1, int $decimalDigits = 2): float
    {
        return $this->fromBaseUnits($this->toBaseUnits($unit), 1, $decimalDigits);
    }

    public function toKilojoules(int $unit = 1, int $decimalDigits = 2): float
    {
        return $this->fromBaseUnits($this->toBaseUnits($unit), 1000, $decimalDigits);
    }

    public function toMegajoules(int $unit = 1, int $decimalDigits = 2): float
    {
        return $this->fromBaseUnits($this->toBaseUnits($unit), 1_000_000, $decimalDigits);
    }

    public function toKilowattHours(int $unit = 1, int $decimalDigits = 2): float
    {
        return $this->fromBaseUnits($this->toBaseUnits($unit), 3_600_000, $decimalDigits);
    }

    public function toCalories(int $unit = 1, int $decimalDigits = 2): float
    {
        return $this->fromBaseUnits($this->toBaseUnits($unit), 4.184, $decimalDigits);
    }

    public function toBtu(int $unit = 1, int $decimalDigits = 2): float
    {
        return $this->fromBaseUnits($this->toBaseUnits($unit), 1055.05585262, $decimalDigits);
    }

    private function baseUnitsPerUnit(): float
    {
        return match ($this) {
            self::Joule => 1,
            self::Kilojoule => 1000,
            self::Megajoule => 1_000_000,
            self::KilowattHour => 3_600_000,
            self::Calorie => 4.184,
            self::Btu => 1055.05585262,
        };
    }
}
