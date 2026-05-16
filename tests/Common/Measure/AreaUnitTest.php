<?php

declare(strict_types=1);

use BensonDevs\SuperchargedEnums\Common\Measure\AreaUnit;

test('AreaUnit has ten cases and default SqMm', function () {
    expect(AreaUnit::cases())->toHaveCount(10);
    expect(AreaUnit::default())->toBe(AreaUnit::SqMm);
});

test('AreaUnit tryFrom and find resolve slugs', function () {
    expect(AreaUnit::tryFrom('sq_m'))->toBe(AreaUnit::SqM);
    expect(AreaUnit::tryFrom('hectare'))->toBe(AreaUnit::Hectare);
    expect(AreaUnit::tryFrom('parsec'))->toBeNull();
    expect(AreaUnit::find('acre'))->toBe(AreaUnit::Acre);
    expect(AreaUnit::find(null))->toBeNull();
});
