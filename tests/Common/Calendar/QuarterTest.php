<?php

declare(strict_types=1);

use BensonDevs\SuperchargedEnums\Common\Calendar\Quarter;

test('Quarter has four cases and default Q1', function () {
    expect(Quarter::cases())->toHaveCount(4);
    expect(Quarter::default())->toBe(Quarter::Q1);
});

test('Quarter tryFrom and find resolve slugs', function () {
    expect(Quarter::tryFrom('q1'))->toBe(Quarter::Q1);
    expect(Quarter::tryFrom('q4'))->toBe(Quarter::Q4);
    expect(Quarter::tryFrom('q5'))->toBeNull();
    expect(Quarter::find('q2'))->toBe(Quarter::Q2);
});
