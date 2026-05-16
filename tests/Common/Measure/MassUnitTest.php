<?php

declare(strict_types=1);

use BensonDevs\SuperchargedEnums\Common\Measure\MassUnit;

test('MassUnit has six cases and default Milligram', function () {
    expect(MassUnit::cases())->toHaveCount(6);
    expect(MassUnit::default())->toBe(MassUnit::Milligram);
});

test('MassUnit tryFrom and find resolve slugs', function () {
    expect(MassUnit::tryFrom('kilogram'))->toBe(MassUnit::Kilogram);
    expect(MassUnit::tryFrom('tonne'))->toBe(MassUnit::Tonne);
    expect(MassUnit::tryFrom('stone'))->toBeNull();
    expect(MassUnit::find('pound'))->toBe(MassUnit::Pound);
    expect(MassUnit::find(null))->toBeNull();
});
