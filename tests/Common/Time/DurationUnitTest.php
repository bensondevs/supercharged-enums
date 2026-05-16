<?php

declare(strict_types=1);

use BensonDevs\SuperchargedEnums\Common\Time\DurationUnit;

test('DurationUnit has five cases and default Second', function () {
    expect(DurationUnit::cases())->toHaveCount(5);
    expect(DurationUnit::default())->toBe(DurationUnit::Second);
});

test('DurationUnit tryFrom and find resolve slugs', function () {
    expect(DurationUnit::tryFrom('second'))->toBe(DurationUnit::Second);
    expect(DurationUnit::tryFrom('week'))->toBe(DurationUnit::Week);
    expect(DurationUnit::tryFrom('eon'))->toBeNull();
    expect(DurationUnit::find('hour'))->toBe(DurationUnit::Hour);
    expect(DurationUnit::find(null))->toBeNull();
});

test('DurationUnit toSeconds returns exact int', function () {
    expect(DurationUnit::Hour->toSeconds(2))->toBe(7200);
    expect(DurationUnit::Week->toSeconds(1))->toBe(604800);
});

test('DurationUnit toMinutes converts with two decimal places', function () {
    expect(DurationUnit::Hour->toMinutes(1))->toBe(60.0);
    expect(DurationUnit::Second->toMinutes(90))->toBe(1.5);
});

test('DurationUnit toHours converts with two decimal places', function () {
    expect(DurationUnit::Second->toHours(1))->toBe(0.0);
    expect(DurationUnit::Second->toHours(3661))->toBe(1.02);
});

test('DurationUnit toDays converts with two decimal places', function () {
    expect(DurationUnit::Week->toDays(1))->toBe(7.0);
});

test('DurationUnit toWeeks converts with two decimal places', function () {
    expect(DurationUnit::Day->toWeeks(14))->toBe(2.0);
});
