<?php

declare(strict_types=1);

namespace BensonDevs\SuperchargedEnums\Common\Time;

use BensonDevs\SuperchargedEnums\EnumExtension;

/**
 * Wall-clock style duration step sizes, smallest to largest. Case order defines {@see EnumExtension::default()}.
 *
 * For sub-second precision labels, see {@see TimePrecision}. Backing values are lowercase English slugs.
 */
enum DurationUnit: string
{
    use EnumExtension;

    case Second = 'second';

    case Minute = 'minute';

    case Hour = 'hour';

    case Day = 'day';

    case Week = 'week';

    public function toSeconds(int $unit = 1): int
    {
        return $unit * $this->secondsPerUnit();
    }

    public function toMinutes(int $unit = 1): float
    {
        return $this->fromSeconds($this->toSeconds($unit), 60);
    }

    public function toHours(int $unit = 1): float
    {
        return $this->fromSeconds($this->toSeconds($unit), 3600);
    }

    public function toDays(int $unit = 1): float
    {
        return $this->fromSeconds($this->toSeconds($unit), 86400);
    }

    public function toWeeks(int $unit = 1): float
    {
        return $this->fromSeconds($this->toSeconds($unit), 604800);
    }

    private function secondsPerUnit(): int
    {
        return match ($this) {
            self::Second => 1,
            self::Minute => 60,
            self::Hour => 3600,
            self::Day => 86400,
            self::Week => 604800,
        };
    }

    private function fromSeconds(int $seconds, int $divisor): float
    {
        return round($seconds / $divisor, 2);
    }
}
