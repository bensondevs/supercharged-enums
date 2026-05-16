<?php

declare(strict_types=1);

use BensonDevs\SuperchargedEnums\Common\Measure\PowerUnit;

test('PowerUnit has four cases and default Watt', function () {
    expect(PowerUnit::cases())->toHaveCount(4);
    expect(PowerUnit::default())->toBe(PowerUnit::Watt);
});

test('PowerUnit tryFrom and find resolve slugs', function () {
    expect(PowerUnit::tryFrom('kilowatt'))->toBe(PowerUnit::Kilowatt);
    expect(PowerUnit::tryFrom('horsepower'))->toBe(PowerUnit::Horsepower);
    expect(PowerUnit::tryFrom('volt_ampere'))->toBeNull();
    expect(PowerUnit::find('megawatt'))->toBe(PowerUnit::Megawatt);
    expect(PowerUnit::find(null))->toBeNull();
});
