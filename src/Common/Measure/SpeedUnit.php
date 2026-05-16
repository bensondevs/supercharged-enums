<?php

declare(strict_types=1);

namespace BensonDevs\SuperchargedEnums\Common\Measure;

use BensonDevs\SuperchargedEnums\Common\Measure\Concerns\ConvertsMeasureUnits;
use BensonDevs\SuperchargedEnums\EnumExtension;

/**
 * Common speed units. Metric first, then US customary and nautical. Case order defines {@see EnumExtension::default()}.
 *
 * Conversions use meters per second as the base unit. Backing values are lowercase English slugs.
 */
enum SpeedUnit: string
{
    use ConvertsMeasureUnits;
    use EnumExtension;

    case MPerS = 'm_per_s';

    case KmPerH = 'km_per_h';

    case Mph = 'mph';

    case Knot = 'knot';

    public function toMetersPerSecond(int $unit = 1, int $decimalDigits = 2): float
    {
        return $this->fromBaseUnits($this->toBaseUnits($unit), 1, $decimalDigits);
    }

    public function toKilometersPerHour(int $unit = 1, int $decimalDigits = 2): float
    {
        return $this->fromBaseUnits($this->toBaseUnits($unit), 0.27777777777778, $decimalDigits);
    }

    public function toMilesPerHour(int $unit = 1, int $decimalDigits = 2): float
    {
        return $this->fromBaseUnits($this->toBaseUnits($unit), 0.44704, $decimalDigits);
    }

    public function toKnots(int $unit = 1, int $decimalDigits = 2): float
    {
        return $this->fromBaseUnits($this->toBaseUnits($unit), 0.51444444444444, $decimalDigits);
    }

    private function baseUnitsPerUnit(): float
    {
        return match ($this) {
            self::MPerS => 1,
            self::KmPerH => 0.27777777777778,
            self::Mph => 0.44704,
            self::Knot => 0.51444444444444,
        };
    }
}
