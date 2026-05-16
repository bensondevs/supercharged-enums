<?php

declare(strict_types=1);

use BensonDevs\SuperchargedEnums\Common\Calendar\DayOfWeek;

test('DayOfWeek has seven cases ISO Monday-first and default Monday', function () {
    expect(DayOfWeek::cases())->toHaveCount(7);
    expect(DayOfWeek::default())->toBe(DayOfWeek::Monday);
});

test('DayOfWeek tryFrom and find resolve slugs', function () {
    expect(DayOfWeek::tryFrom('monday'))->toBe(DayOfWeek::Monday);
    expect(DayOfWeek::tryFrom('sunday'))->toBe(DayOfWeek::Sunday);
    expect(DayOfWeek::tryFrom('nope'))->toBeNull();
    expect(DayOfWeek::find('monday'))->toBe(DayOfWeek::Monday);
    expect(DayOfWeek::find(null))->toBeNull();
});
