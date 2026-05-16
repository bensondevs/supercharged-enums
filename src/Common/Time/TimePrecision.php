<?php

declare(strict_types=1);

namespace BensonDevs\SuperchargedEnums\Common\Time;

use BensonDevs\SuperchargedEnums\Common\Time\Concerns\ConvertsTimePrecision;
use BensonDevs\SuperchargedEnums\EnumExtension;

/**
 * Time measurement precision steps, coarsest to finest. Case order defines {@see EnumExtension::default()}.
 *
 * Conversions use nanoseconds as the base unit. Backing values are lowercase English slugs.
 */
enum TimePrecision: string
{
    use ConvertsTimePrecision;
    use EnumExtension;

    case Second = 'second';

    case Millisecond = 'millisecond';

    case Microsecond = 'microsecond';

    case Nanosecond = 'nanosecond';

    public function toSeconds(int $unit = 1, int $decimalDigits = 2): float
    {
        return $this->fromNanoseconds($this->toNanoseconds($unit), 1_000_000_000, $decimalDigits);
    }

    public function toMilliseconds(int $unit = 1, int $decimalDigits = 2): float
    {
        return $this->fromNanoseconds($this->toNanoseconds($unit), 1_000_000, $decimalDigits);
    }

    public function toMicroseconds(int $unit = 1, int $decimalDigits = 2): float
    {
        return $this->fromNanoseconds($this->toNanoseconds($unit), 1_000, $decimalDigits);
    }

    private function nanosecondsPerUnit(): int
    {
        return match ($this) {
            self::Second => 1_000_000_000,
            self::Millisecond => 1_000_000,
            self::Microsecond => 1_000,
            self::Nanosecond => 1,
        };
    }
}
