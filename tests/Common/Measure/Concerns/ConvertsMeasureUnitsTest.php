<?php

declare(strict_types=1);

use BensonDevs\SuperchargedEnums\Common\Measure\AreaUnit;
use BensonDevs\SuperchargedEnums\Common\Measure\EnergyUnit;
use BensonDevs\SuperchargedEnums\Common\Measure\FrequencyUnit;
use BensonDevs\SuperchargedEnums\Common\Measure\LengthUnit;
use BensonDevs\SuperchargedEnums\Common\Measure\MassUnit;
use BensonDevs\SuperchargedEnums\Common\Measure\PowerUnit;
use BensonDevs\SuperchargedEnums\Common\Measure\PressureUnit;
use BensonDevs\SuperchargedEnums\Common\Measure\SpeedUnit;
use BensonDevs\SuperchargedEnums\Common\Measure\VolumeUnit;

test('LengthUnit converts length with decimal precision', function () {
    expect(LengthUnit::Meter->toMillimeters(1))->toBe(1000.0);
    expect(LengthUnit::Inch->toMillimeters(2))->toBe(50.8);
    expect(LengthUnit::Foot->toYards(3))->toBe(1.0);
});

test('MassUnit converts mass with decimal precision', function () {
    expect(MassUnit::Kilogram->toGrams(1))->toBe(1000.0);
    expect(MassUnit::Pound->toGrams(1, 4))->toBe(453.5924);
});

test('VolumeUnit converts US liquid volume', function () {
    expect(VolumeUnit::Liter->toMilliliters(2))->toBe(2000.0);
    expect(VolumeUnit::UsCup->toUsFluidOunces(1))->toBe(8.0);
});

test('AreaUnit converts area with decimal precision', function () {
    expect(AreaUnit::SqM->toSqMillimeters(1))->toBe(1_000_000.0);
    expect(AreaUnit::Hectare->toSqMeters(1))->toBe(10_000.0);
});

test('SpeedUnit converts speed with decimal precision', function () {
    expect(SpeedUnit::MPerS->toKilometersPerHour(1))->toBe(3.6);
    expect(SpeedUnit::KmPerH->toMetersPerSecond(36))->toBe(10.0);
});

test('PressureUnit converts pressure with decimal precision', function () {
    expect(PressureUnit::Bar->toPascals(1))->toBe(100_000.0);
    expect(PressureUnit::Atmosphere->toKilopascals(1))->toBe(101.33);
});

test('EnergyUnit converts energy with decimal precision', function () {
    expect(EnergyUnit::Kilojoule->toJoules(1))->toBe(1000.0);
    expect(EnergyUnit::KilowattHour->toMegajoules(1))->toBe(3.6);
});

test('PowerUnit converts power with decimal precision', function () {
    expect(PowerUnit::Kilowatt->toWatts(1))->toBe(1000.0);
    expect(PowerUnit::Horsepower->toKilowatts(1))->toBe(0.75);
});

test('FrequencyUnit converts frequency with decimal precision', function () {
    expect(FrequencyUnit::Megahertz->toHertz(1))->toBe(1_000_000.0);
    expect(FrequencyUnit::Gigahertz->toMegahertz(1))->toBe(1000.0);
});
