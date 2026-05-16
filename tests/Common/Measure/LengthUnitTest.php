<?php

declare(strict_types=1);

use BensonDevs\SuperchargedEnums\Common\Measure\LengthUnit;

test('LengthUnit has eight cases and default Millimeter', function () {
    expect(LengthUnit::cases())->toHaveCount(8);
    expect(LengthUnit::default())->toBe(LengthUnit::Millimeter);
});

test('LengthUnit tryFrom and find resolve slugs', function () {
    expect(LengthUnit::tryFrom('mm'))->toBe(LengthUnit::Millimeter);
    expect(LengthUnit::tryFrom('km'))->toBe(LengthUnit::Kilometer);
    expect(LengthUnit::tryFrom('mile'))->toBe(LengthUnit::Mile);
    expect(LengthUnit::tryFrom('parsec'))->toBeNull();
    expect(LengthUnit::find('inch'))->toBe(LengthUnit::Inch);
    expect(LengthUnit::find(null))->toBeNull();
});
