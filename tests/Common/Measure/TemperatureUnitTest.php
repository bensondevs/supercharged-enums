<?php

declare(strict_types=1);

use BensonDevs\SuperchargedEnums\Common\Measure\TemperatureUnit;

test('TemperatureUnit has three cases and default Celsius', function () {
    expect(TemperatureUnit::cases())->toHaveCount(3);
    expect(TemperatureUnit::default())->toBe(TemperatureUnit::Celsius);
});

test('TemperatureUnit tryFrom and find resolve slugs', function () {
    expect(TemperatureUnit::tryFrom('fahrenheit'))->toBe(TemperatureUnit::Fahrenheit);
    expect(TemperatureUnit::tryFrom('rankine'))->toBeNull();
    expect(TemperatureUnit::find('kelvin'))->toBe(TemperatureUnit::Kelvin);
    expect(TemperatureUnit::find(null))->toBeNull();
});

test('TemperatureUnit converts between scales', function () {
    expect(TemperatureUnit::Celsius->toFahrenheit(100))->toBe(212.0);
    expect(TemperatureUnit::Fahrenheit->toCelsius(32))->toBe(0.0);
    expect(TemperatureUnit::Kelvin->toCelsius(273.15))->toBe(0.0);
});
