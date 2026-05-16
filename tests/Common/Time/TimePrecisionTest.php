<?php

declare(strict_types=1);

use BensonDevs\SuperchargedEnums\Common\Time\TimePrecision;

test('TimePrecision has four cases and default Second', function () {
    expect(TimePrecision::cases())->toHaveCount(4);
    expect(TimePrecision::default())->toBe(TimePrecision::Second);
});

test('TimePrecision tryFrom and find resolve slugs', function () {
    expect(TimePrecision::tryFrom('millisecond'))->toBe(TimePrecision::Millisecond);
    expect(TimePrecision::tryFrom('nanosecond'))->toBe(TimePrecision::Nanosecond);
    expect(TimePrecision::tryFrom('picosecond'))->toBeNull();
    expect(TimePrecision::find('microsecond'))->toBe(TimePrecision::Microsecond);
    expect(TimePrecision::find(null))->toBeNull();
});
