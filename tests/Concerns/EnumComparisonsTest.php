<?php

declare(strict_types=1);

use BensonDevs\SuperchargedEnums\Tests\Fixtures\DeclarationOrderEnum;
use BensonDevs\SuperchargedEnums\Tests\Fixtures\EnumWithAliases;
use BensonDevs\SuperchargedEnums\Tests\Fixtures\PartialCompositionEnum;
use BensonDevs\SuperchargedEnums\Tests\Fixtures\StringSampleEnum;

test('comparisons normalize scalars', function () {
    $enum = StringSampleEnum::NoShow;

    expect($enum->is(null))->toBeFalse();
    expect($enum->is(StringSampleEnum::NoShow))->toBeTrue();
    expect($enum->is('no_show'))->toBeTrue();
    expect($enum->is('first'))->toBeFalse();
    expect($enum->isNot('first'))->toBeTrue();
});

test('isIn ignores unresolvable values', function () {
    $enum = StringSampleEnum::NoShow;

    expect($enum->isIn(['no_show', 'bad', StringSampleEnum::FirstOption]))->toBeTrue();
    expect($enum->isIn(['first']))->toBeFalse();
});

test('isAfter and isBefore use declaration order', function () {
    expect(PartialCompositionEnum::Left->isAfter(PartialCompositionEnum::Right))->toBeFalse();
    expect(PartialCompositionEnum::Right->isAfter(PartialCompositionEnum::Left))->toBeTrue();
    expect(PartialCompositionEnum::Left->isBefore('right'))->toBeTrue();
    expect(PartialCompositionEnum::Right->isBefore('left'))->toBeFalse();
});

test('isAfterOrEqual and isBeforeOrEqual include equality', function () {
    expect(PartialCompositionEnum::Left->isAfterOrEqual(PartialCompositionEnum::Left))->toBeTrue();
    expect(PartialCompositionEnum::Left->isAfterOrEqual(PartialCompositionEnum::Right))->toBeFalse();
    expect(PartialCompositionEnum::Right->isBeforeOrEqual(PartialCompositionEnum::Right))->toBeTrue();
    expect(PartialCompositionEnum::Right->isBeforeOrEqual(PartialCompositionEnum::Left))->toBeFalse();
});

test('compareTo returns signed index comparison', function () {
    expect(PartialCompositionEnum::Left->compareTo(PartialCompositionEnum::Right))->toBe(-1);
    expect(PartialCompositionEnum::Right->compareTo(PartialCompositionEnum::Left))->toBe(1);
    expect(PartialCompositionEnum::Left->compareTo(PartialCompositionEnum::Left))->toBe(0);
    expect(PartialCompositionEnum::Left->compareTo(null))->toBeNull();
    expect(PartialCompositionEnum::Left->compareTo('missing'))->toBeNull();
});

test('ordering ignores backing value when declaration order differs', function () {
    expect(DeclarationOrderEnum::First->isBefore(DeclarationOrderEnum::Second))->toBeTrue();
    expect(DeclarationOrderEnum::First->isAfter(1))->toBeFalse();
    expect(DeclarationOrderEnum::Second->isAfter(2))->toBeTrue();
    expect(DeclarationOrderEnum::First->compareTo(1))->toBe(-1);
});

test('ordering resolves aliases like is()', function () {
    expect(EnumWithAliases::Active->isAfter('first'))->toBeTrue();
    expect(EnumWithAliases::First->isBefore('legacy_active'))->toBeTrue();
});

test('isBetween is inclusive by default', function () {
    expect(PartialCompositionEnum::Left->isBetween(
        PartialCompositionEnum::Left,
        PartialCompositionEnum::Right,
    ))->toBeTrue();

    expect(PartialCompositionEnum::Right->isBetween(
        PartialCompositionEnum::Left,
        PartialCompositionEnum::Right,
    ))->toBeTrue();
});

test('isBetween supports exclusive boundaries', function () {
    expect(PartialCompositionEnum::Left->isBetween(
        PartialCompositionEnum::Left,
        PartialCompositionEnum::Right,
        includeStart: false,
    ))->toBeFalse();

    expect(PartialCompositionEnum::Left->isBetween(
        PartialCompositionEnum::Left,
        PartialCompositionEnum::Right,
        includeEnd: false,
    ))->toBeTrue();
});

test('isBetween swaps reversed bounds', function () {
    expect(PartialCompositionEnum::Left->isBetween(
        PartialCompositionEnum::Right,
        PartialCompositionEnum::Left,
    ))->toBeTrue();
});

test('isBetween returns false for unresolvable bounds', function () {
    expect(PartialCompositionEnum::Left->isBetween('left', 'missing'))->toBeFalse();
    expect(PartialCompositionEnum::Left->isBetween(null, PartialCompositionEnum::Right))->toBeFalse();
});

test('isAfter returns false for null operand', function () {
    expect(PartialCompositionEnum::Left->isAfter(null))->toBeFalse();
});

test('isFirst and isLast reflect case positions', function () {
    expect(PartialCompositionEnum::Left->isFirst())->toBeTrue();
    expect(PartialCompositionEnum::Left->isLast())->toBeFalse();
    expect(PartialCompositionEnum::Right->isLast())->toBeTrue();
    expect(PartialCompositionEnum::Right->isFirst())->toBeFalse();
});

test('next and previous step through cases', function () {
    expect(PartialCompositionEnum::Left->next())->toBe(PartialCompositionEnum::Right);
    expect(PartialCompositionEnum::Right->next())->toBeNull();
    expect(PartialCompositionEnum::Right->previous())->toBe(PartialCompositionEnum::Left);
    expect(PartialCompositionEnum::Left->previous())->toBeNull();
});

test('next and previous wrap when requested', function () {
    expect(PartialCompositionEnum::Right->next(wrap: true))->toBe(PartialCompositionEnum::Left);
    expect(PartialCompositionEnum::Left->previous(wrap: true))->toBe(PartialCompositionEnum::Right);
});

test('diff returns signed index distance', function () {
    expect(PartialCompositionEnum::Right->diff(PartialCompositionEnum::Left))->toBe(1);
    expect(PartialCompositionEnum::Left->diff(PartialCompositionEnum::Right))->toBe(-1);
    expect(PartialCompositionEnum::Left->diff(null))->toBeNull();
});

test('min and max pick extremes by declaration order', function () {
    expect(PartialCompositionEnum::min(
        PartialCompositionEnum::Right,
        PartialCompositionEnum::Left,
    ))->toBe(PartialCompositionEnum::Left);

    expect(PartialCompositionEnum::max('right', 'left'))->toBe(PartialCompositionEnum::Right);
    expect(PartialCompositionEnum::min('missing'))->toBeNull();
    expect(PartialCompositionEnum::max('nope', null))->toBeNull();
});

test('min and max ignore unresolvable operands', function () {
    expect(PartialCompositionEnum::min('missing', PartialCompositionEnum::Right))->toBe(PartialCompositionEnum::Right);
});
