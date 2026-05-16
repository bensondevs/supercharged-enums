<?php

declare(strict_types=1);

namespace BensonDevs\SuperchargedEnums\Common\DataSize;

use BensonDevs\SuperchargedEnums\Common\DataSize\Concerns\ConvertsDataSizeUnits;
use BensonDevs\SuperchargedEnums\EnumExtension;

/**
 * Data size steps using **decimal** SI-style prefixes: each step above {@see self::Byte} multiplies by **1000**. Case order defines {@see EnumExtension::default()}.
 *
 * For powers of 1024 (IEC binary), use {@see BinaryDataSizeUnit}.
 *
 * Backing values are lowercase English slugs.
 */
enum DecimalDataSizeUnit: string
{
    use ConvertsDataSizeUnits;
    use EnumExtension;

    case Bit = 'bit';

    case Byte = 'byte';

    case Kilobyte = 'kilobyte';

    case Megabyte = 'megabyte';

    case Gigabyte = 'gigabyte';

    case Terabyte = 'terabyte';

    case Petabyte = 'petabyte';

    case Exabyte = 'exabyte';

    public function toBytes(int $unit = 1, int $decimalDigits = 2): float
    {
        return $this->fromBits($this->toBits($unit), 8, $decimalDigits);
    }

    public function toKilobytes(int $unit = 1, int $decimalDigits = 2): float
    {
        return $this->fromBits($this->toBits($unit), 8_000, $decimalDigits);
    }

    public function toMegabytes(int $unit = 1, int $decimalDigits = 2): float
    {
        return $this->fromBits($this->toBits($unit), 8_000_000, $decimalDigits);
    }

    public function toGigabytes(int $unit = 1, int $decimalDigits = 2): float
    {
        return $this->fromBits($this->toBits($unit), 8_000_000_000, $decimalDigits);
    }

    public function toTerabytes(int $unit = 1, int $decimalDigits = 2): float
    {
        return $this->fromBits($this->toBits($unit), 8_000_000_000_000, $decimalDigits);
    }

    public function toPetabytes(int $unit = 1, int $decimalDigits = 2): float
    {
        return $this->fromBits($this->toBits($unit), 8_000_000_000_000_000, $decimalDigits);
    }

    public function toExabytes(int $unit = 1, int $decimalDigits = 2): float
    {
        return $this->fromBits($this->toBits($unit), 8_000_000_000_000_000_000, $decimalDigits);
    }

    private function bitsPerUnit(): int
    {
        return match ($this) {
            self::Bit => 1,
            self::Byte => 8,
            self::Kilobyte => 8 * 1_000,
            self::Megabyte => 8 * 1_000 ** 2,
            self::Gigabyte => 8 * 1_000 ** 3,
            self::Terabyte => 8 * 1_000 ** 4,
            self::Petabyte => 8 * 1_000 ** 5,
            self::Exabyte => 8 * 1_000 ** 6,
        };
    }
}
