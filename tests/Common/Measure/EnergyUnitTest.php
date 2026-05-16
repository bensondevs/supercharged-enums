<?php

declare(strict_types=1);

use BensonDevs\SuperchargedEnums\Common\Measure\EnergyUnit;

test('EnergyUnit has six cases and default Joule', function () {
    expect(EnergyUnit::cases())->toHaveCount(6);
    expect(EnergyUnit::default())->toBe(EnergyUnit::Joule);
});

test('EnergyUnit tryFrom and find resolve slugs', function () {
    expect(EnergyUnit::tryFrom('kilowatt_hour'))->toBe(EnergyUnit::KilowattHour);
    expect(EnergyUnit::tryFrom('btu'))->toBe(EnergyUnit::Btu);
    expect(EnergyUnit::tryFrom('erg'))->toBeNull();
    expect(EnergyUnit::find('calorie'))->toBe(EnergyUnit::Calorie);
    expect(EnergyUnit::find(null))->toBeNull();
});
