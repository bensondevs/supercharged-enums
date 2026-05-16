<?php

declare(strict_types=1);

use BensonDevs\SuperchargedEnums\Common\Time\TimePrecision;

test('TimePrecision toNanoseconds returns exact int', function () {
    expect(TimePrecision::Second->toNanoseconds(1))->toBe(1_000_000_000);
    expect(TimePrecision::Millisecond->toNanoseconds(1))->toBe(1_000_000);
    expect(TimePrecision::Microsecond->toNanoseconds(1))->toBe(1_000);
    expect(TimePrecision::Nanosecond->toNanoseconds(1))->toBe(1);
});

test('TimePrecision toSeconds converts with two decimal places', function () {
    expect(TimePrecision::Millisecond->toSeconds(1500))->toBe(1.5);
    expect(TimePrecision::Microsecond->toSeconds(1_000_000))->toBe(1.0);
    expect(TimePrecision::Nanosecond->toSeconds(1_000_000_000))->toBe(1.0);
});

test('TimePrecision toMilliseconds converts with two decimal places', function () {
    expect(TimePrecision::Second->toMilliseconds(1))->toBe(1000.0);
    expect(TimePrecision::Microsecond->toMilliseconds(1000))->toBe(1.0);
});

test('TimePrecision toMicroseconds converts with two decimal places', function () {
    expect(TimePrecision::Millisecond->toMicroseconds(1))->toBe(1000.0);
    expect(TimePrecision::Second->toMicroseconds(1))->toBe(1_000_000.0);
});

test('TimePrecision respects decimalDigits', function () {
    expect(TimePrecision::Millisecond->toSeconds(1, decimalDigits: 6))->toBe(0.001);
});
