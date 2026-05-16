<?php

declare(strict_types=1);

namespace BensonDevs\SuperchargedEnums\Common\Measure\Concerns;

trait ConvertsMeasureUnits
{
    abstract private function baseUnitsPerUnit(): float;

    public function toBaseUnits(int $unit = 1): float
    {
        return $unit * $this->baseUnitsPerUnit();
    }

    private function fromBaseUnits(float $base, float $divisor, int $decimalDigits): float
    {
        return round($base / $divisor, $decimalDigits);
    }
}
