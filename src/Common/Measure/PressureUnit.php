<?php

declare(strict_types=1);

namespace BensonDevs\SuperchargedEnums\Common\Measure;

use BensonDevs\SuperchargedEnums\Common\Measure\Concerns\ConvertsMeasureUnits;
use BensonDevs\SuperchargedEnums\EnumExtension;

/**
 * Common pressure units. SI smallest-to-largest, then US customary. Case order defines {@see EnumExtension::default()}.
 *
 * Conversions use pascals as the base unit. Backing values are lowercase English slugs.
 */
enum PressureUnit: string
{
    use ConvertsMeasureUnits;
    use EnumExtension;

    case Pascal = 'pascal';

    case Kilopascal = 'kilopascal';

    case Bar = 'bar';

    case Psi = 'psi';

    case Atmosphere = 'atmosphere';

    public function toPascals(int $unit = 1, int $decimalDigits = 2): float
    {
        return $this->fromBaseUnits($this->toBaseUnits($unit), 1, $decimalDigits);
    }

    public function toKilopascals(int $unit = 1, int $decimalDigits = 2): float
    {
        return $this->fromBaseUnits($this->toBaseUnits($unit), 1000, $decimalDigits);
    }

    public function toBars(int $unit = 1, int $decimalDigits = 2): float
    {
        return $this->fromBaseUnits($this->toBaseUnits($unit), 100_000, $decimalDigits);
    }

    public function toPsi(int $unit = 1, int $decimalDigits = 2): float
    {
        return $this->fromBaseUnits($this->toBaseUnits($unit), 6894.757293168, $decimalDigits);
    }

    public function toAtmospheres(int $unit = 1, int $decimalDigits = 2): float
    {
        return $this->fromBaseUnits($this->toBaseUnits($unit), 101_325, $decimalDigits);
    }

    private function baseUnitsPerUnit(): float
    {
        return match ($this) {
            self::Pascal => 1,
            self::Kilopascal => 1000,
            self::Bar => 100_000,
            self::Psi => 6894.757293168,
            self::Atmosphere => 101_325,
        };
    }
}
