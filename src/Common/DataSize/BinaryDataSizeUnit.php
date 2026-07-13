<?php

declare(strict_types=1);

namespace BensonDevs\SuperchargedEnums\Common\DataSize;

use BensonDevs\SuperchargedEnums\Common\DataSize\Concerns\ConvertsDataSizeUnits;
use BensonDevs\SuperchargedEnums\EnumExtension;

/**
 * Data size steps using **binary** IEC prefixes: each step above {@see self::Byte} multiplies by **1024**. Case order defines {@see EnumExtension::default()}.
 *
 * For powers of 1000 (decimal), use {@see DecimalDataSizeUnit}.
 *
 * Backing values are lowercase English slugs.
 */
enum BinaryDataSizeUnit: string
{
    use ConvertsDataSizeUnits;
    use EnumExtension;

    case Bit = 'bit';

    case Byte = 'byte';

    case Kibibyte = 'kibibyte';

    case Mebibyte = 'mebibyte';

    case Gibibyte = 'gibibyte';

    case Tebibyte = 'tebibyte';

    case Pebibyte = 'pebibyte';

    case Exbibyte = 'exbibyte';

    public function toBytes(int $unit = 1, int $decimalDigits = 2): float
    {
        return $this->fromBits($this->toBits($unit), 8, $decimalDigits);
    }

    public function toKibibytes(int $unit = 1, int $decimalDigits = 2): float
    {
        return $this->fromBits($this->toBits($unit), 8_192, $decimalDigits);
    }

    public function toMebibytes(int $unit = 1, int $decimalDigits = 2): float
    {
        return $this->fromBits($this->toBits($unit), 8_388_608, $decimalDigits);
    }

    public function toGibibytes(int $unit = 1, int $decimalDigits = 2): float
    {
        return $this->fromBits($this->toBits($unit), (int) (8 * 1_024 ** 3), $decimalDigits);
    }

    public function toTebibytes(int $unit = 1, int $decimalDigits = 2): float
    {
        return $this->fromBits($this->toBits($unit), (int) (8 * 1_024 ** 4), $decimalDigits);
    }

    public function toPebibytes(int $unit = 1, int $decimalDigits = 2): float
    {
        return $this->fromBits($this->toBits($unit), (int) (8 * 1_024 ** 5), $decimalDigits);
    }

    public function toExbibytes(int $unit = 1, int $decimalDigits = 2): float
    {
        return $this->fromBits($this->toBits($unit), (int) (8 * 1_024 ** 6), $decimalDigits);
    }

    private function bitsPerUnit(): int
    {
        return match ($this) {
            self::Bit => 1,
            self::Byte => 8,
            self::Kibibyte => 8 * 1_024,
            self::Mebibyte => 8 * 1_024 ** 2,
            self::Gibibyte => (int) (8 * 1_024 ** 3),
            self::Tebibyte => (int) (8 * 1_024 ** 4),
            self::Pebibyte => (int) (8 * 1_024 ** 5),
            self::Exbibyte => (int) (8 * 1_024 ** 6),
        };
    }
}
