<?php

declare(strict_types=1);

use BensonDevs\SuperchargedEnums\Common\Measure\PressureUnit;

test('PressureUnit has five cases and default Pascal', function () {
    expect(PressureUnit::cases())->toHaveCount(5);
    expect(PressureUnit::default())->toBe(PressureUnit::Pascal);
});

test('PressureUnit tryFrom and find resolve slugs', function () {
    expect(PressureUnit::tryFrom('bar'))->toBe(PressureUnit::Bar);
    expect(PressureUnit::tryFrom('psi'))->toBe(PressureUnit::Psi);
    expect(PressureUnit::tryFrom('torr'))->toBeNull();
    expect(PressureUnit::find('atmosphere'))->toBe(PressureUnit::Atmosphere);
    expect(PressureUnit::find(null))->toBeNull();
});
