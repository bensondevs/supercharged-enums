<?php

declare(strict_types=1);

use BensonDevs\SuperchargedEnums\Common\Angle\AngleUnit;

test('AngleUnit has three cases and default Degree', function () {
    expect(AngleUnit::cases())->toHaveCount(3);
    expect(AngleUnit::default())->toBe(AngleUnit::Degree);
});

test('AngleUnit tryFrom and find resolve slugs', function () {
    expect(AngleUnit::tryFrom('radian'))->toBe(AngleUnit::Radian);
    expect(AngleUnit::tryFrom('steradian'))->toBeNull();
    expect(AngleUnit::find('gradian'))->toBe(AngleUnit::Gradian);
    expect(AngleUnit::find(null))->toBeNull();
});
