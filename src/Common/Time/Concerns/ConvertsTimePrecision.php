<?php

declare(strict_types=1);

namespace BensonDevs\SuperchargedEnums\Common\Time\Concerns;

trait ConvertsTimePrecision
{
    abstract private function nanosecondsPerUnit(): int;

    public function toNanoseconds(int $unit = 1): int
    {
        return $unit * $this->nanosecondsPerUnit();
    }

    private function fromNanoseconds(int $nanoseconds, int $divisor, int $decimalDigits): float
    {
        return round($nanoseconds / $divisor, $decimalDigits);
    }
}
