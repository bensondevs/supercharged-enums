<?php

declare(strict_types=1);

use BensonDevs\SuperchargedEnums\Common\Measure\VolumeUnit;

test('VolumeUnit has seven cases and default Milliliter', function () {
    expect(VolumeUnit::cases())->toHaveCount(7);
    expect(VolumeUnit::default())->toBe(VolumeUnit::Milliliter);
});

test('VolumeUnit tryFrom and find resolve slugs', function () {
    expect(VolumeUnit::tryFrom('liter'))->toBe(VolumeUnit::Liter);
    expect(VolumeUnit::tryFrom('us_liquid_gallon'))->toBe(VolumeUnit::UsLiquidGallon);
    expect(VolumeUnit::tryFrom('imperial_pint'))->toBeNull();
    expect(VolumeUnit::find('us_cup'))->toBe(VolumeUnit::UsCup);
    expect(VolumeUnit::find(null))->toBeNull();
});
