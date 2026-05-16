<?php

declare(strict_types=1);

use BensonDevs\SuperchargedEnums\Common\Calendar\Month;

test('Month has twelve cases and default January', function () {
    expect(Month::cases())->toHaveCount(12);
    expect(Month::default())->toBe(Month::January);
});

test('Month tryFrom and find resolve slugs', function () {
    expect(Month::tryFrom('january'))->toBe(Month::January);
    expect(Month::tryFrom('december'))->toBe(Month::December);
    expect(Month::tryFrom(''))->toBeNull();
    expect(Month::find('december'))->toBe(Month::December);
});

test('Month ordering follows declaration order', function () {
    expect(Month::March->isBetween(Month::February, Month::April))->toBeTrue();
    expect(Month::January->isFirst())->toBeTrue();
    expect(Month::December->isLast())->toBeTrue();
});
