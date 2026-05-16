<?php

declare(strict_types=1);

namespace BensonDevs\SuperchargedEnums\Common\Measure;

use BensonDevs\SuperchargedEnums\EnumExtension;

/**
 * Common temperature scales. Case order defines {@see EnumExtension::default()}.
 *
 * Conversions use Celsius as the canonical intermediate (affine transforms, not multiplicative).
 * Backing values are lowercase English slugs.
 */
enum TemperatureUnit: string
{
    use EnumExtension;

    case Celsius = 'celsius';

    case Fahrenheit = 'fahrenheit';

    case Kelvin = 'kelvin';

    public function toCelsius(float $value = 1, int $decimalDigits = 2): float
    {
        return round($this->toCanonicalCelsius($value), $decimalDigits);
    }

    public function toFahrenheit(float $value = 1, int $decimalDigits = 2): float
    {
        $celsius = $this->toCanonicalCelsius($value);

        return round($celsius * 9 / 5 + 32, $decimalDigits);
    }

    public function toKelvin(float $value = 1, int $decimalDigits = 2): float
    {
        $celsius = $this->toCanonicalCelsius($value);

        return round($celsius + 273.15, $decimalDigits);
    }

    private function toCanonicalCelsius(float $value): float
    {
        return match ($this) {
            self::Celsius => $value,
            self::Fahrenheit => ($value - 32) * 5 / 9,
            self::Kelvin => $value - 273.15,
        };
    }
}
