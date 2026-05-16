<?php

declare(strict_types=1);

namespace BensonDevs\SuperchargedEnums\Common\Measure;

use BensonDevs\SuperchargedEnums\Common\Measure\Concerns\ConvertsMeasureUnits;
use BensonDevs\SuperchargedEnums\EnumExtension;

/**
 * Common frequency units. SI smallest-to-largest. Case order defines {@see EnumExtension::default()}.
 *
 * Conversions use hertz as the base unit. Backing values are lowercase English slugs.
 */
enum FrequencyUnit: string
{
    use ConvertsMeasureUnits;
    use EnumExtension;

    case Hertz = 'hertz';

    case Kilohertz = 'kilohertz';

    case Megahertz = 'megahertz';

    case Gigahertz = 'gigahertz';

    public function toHertz(int $unit = 1, int $decimalDigits = 2): float
    {
        return $this->fromBaseUnits($this->toBaseUnits($unit), 1, $decimalDigits);
    }

    public function toKilohertz(int $unit = 1, int $decimalDigits = 2): float
    {
        return $this->fromBaseUnits($this->toBaseUnits($unit), 1000, $decimalDigits);
    }

    public function toMegahertz(int $unit = 1, int $decimalDigits = 2): float
    {
        return $this->fromBaseUnits($this->toBaseUnits($unit), 1_000_000, $decimalDigits);
    }

    public function toGigahertz(int $unit = 1, int $decimalDigits = 2): float
    {
        return $this->fromBaseUnits($this->toBaseUnits($unit), 1_000_000_000, $decimalDigits);
    }

    private function baseUnitsPerUnit(): float
    {
        return match ($this) {
            self::Hertz => 1,
            self::Kilohertz => 1000,
            self::Megahertz => 1_000_000,
            self::Gigahertz => 1_000_000_000,
        };
    }
}
