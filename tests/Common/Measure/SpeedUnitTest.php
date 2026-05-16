<?php

declare(strict_types=1);

use BensonDevs\SuperchargedEnums\Common\Measure\SpeedUnit;

test('SpeedUnit has four cases and default MPerS', function () {
    expect(SpeedUnit::cases())->toHaveCount(4);
    expect(SpeedUnit::default())->toBe(SpeedUnit::MPerS);
});

test('SpeedUnit tryFrom and find resolve slugs', function () {
    expect(SpeedUnit::tryFrom('km_per_h'))->toBe(SpeedUnit::KmPerH);
    expect(SpeedUnit::tryFrom('mph'))->toBe(SpeedUnit::Mph);
    expect(SpeedUnit::tryFrom('mach'))->toBeNull();
    expect(SpeedUnit::find('knot'))->toBe(SpeedUnit::Knot);
    expect(SpeedUnit::find(null))->toBeNull();
});
