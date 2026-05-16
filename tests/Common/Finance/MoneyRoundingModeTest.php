<?php

declare(strict_types=1);

use BensonDevs\SuperchargedEnums\Common\Finance\MoneyRoundingMode;

test('MoneyRoundingMode has seven cases and default HalfUp', function () {
    expect(MoneyRoundingMode::cases())->toHaveCount(7);
    expect(MoneyRoundingMode::default())->toBe(MoneyRoundingMode::HalfUp);
});

test('MoneyRoundingMode tryFrom and find resolve slugs', function () {
    expect(MoneyRoundingMode::tryFrom('half_even'))->toBe(MoneyRoundingMode::HalfEven);
    expect(MoneyRoundingMode::tryFrom('away_from_zero'))->toBe(MoneyRoundingMode::AwayFromZero);
    expect(MoneyRoundingMode::tryFrom('bankers'))->toBeNull();
    expect(MoneyRoundingMode::find('toward_zero'))->toBe(MoneyRoundingMode::TowardZero);
    expect(MoneyRoundingMode::find(null))->toBeNull();
});

test('MoneyRoundingMode HalfUp rounds half away from zero', function () {
    expect(MoneyRoundingMode::HalfUp->round(2.005, 2))->toBe(2.01);
    expect(MoneyRoundingMode::HalfUp->round(2.004, 2))->toBe(2.0);
});

test('MoneyRoundingMode HalfDown rounds half toward zero', function () {
    expect(MoneyRoundingMode::HalfDown->round(2.005, 2))->toBe(2.0);
});

test('MoneyRoundingMode HalfEven uses bankers rounding', function () {
    expect(MoneyRoundingMode::HalfEven->round(2.5, 0))->toBe(2.0);
    expect(MoneyRoundingMode::HalfEven->round(3.5, 0))->toBe(4.0);
});

test('MoneyRoundingMode Up and Down round toward infinity', function () {
    expect(MoneyRoundingMode::Up->round(1.231, 2))->toBe(1.24);
    expect(MoneyRoundingMode::Down->round(1.231, 2))->toBe(1.23);
});

test('MoneyRoundingMode TowardZero and AwayFromZero handle negatives', function () {
    expect(MoneyRoundingMode::TowardZero->round(-1.239, 2))->toBe(-1.23);
    expect(MoneyRoundingMode::AwayFromZero->round(-1.239, 2))->toBe(-1.24);
});

test('MoneyRoundingMode roundMoney defaults to two decimal places', function () {
    expect(MoneyRoundingMode::HalfUp->roundMoney(10.556))->toBe(10.56);
});

test('MoneyRoundingMode round rejects negative decimal places', function () {
    MoneyRoundingMode::HalfUp->round(1.0, -1);
})->throws(InvalidArgumentException::class);
