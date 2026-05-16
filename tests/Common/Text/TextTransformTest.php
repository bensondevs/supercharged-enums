<?php

declare(strict_types=1);

use BensonDevs\SuperchargedEnums\Common\Text\TextTransform;

test('TextTransform has five cases and default None', function () {
    expect(TextTransform::cases())->toHaveCount(5);
    expect(TextTransform::default())->toBe(TextTransform::None);
});

test('TextTransform tryFrom and find resolve slugs', function () {
    expect(TextTransform::tryFrom('snake'))->toBe(TextTransform::Snake);
    expect(TextTransform::tryFrom('pascal'))->toBe(TextTransform::Pascal);
    expect(TextTransform::tryFrom('screaming_snake'))->toBeNull();
    expect(TextTransform::find('kebab'))->toBe(TextTransform::Kebab);
    expect(TextTransform::find(null))->toBeNull();
});
