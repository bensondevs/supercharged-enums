<?php

declare(strict_types=1);

namespace BensonDevs\SuperchargedEnums\Common\DataSize\Concerns;

trait ConvertsDataSizeUnits
{
    abstract private function bitsPerUnit(): int;

    public function toBits(int $unit = 1): int
    {
        return $unit * $this->bitsPerUnit();
    }

    private function fromBits(int $bits, int $divisor, int $decimalDigits): float
    {
        return round($bits / $divisor, $decimalDigits);
    }
}
