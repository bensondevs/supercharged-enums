<?php

declare(strict_types=1);

use BensonDevs\SuperchargedEnums\Tests\Fixtures\EnumWithAliases;
use BensonDevs\SuperchargedEnums\Tests\Fixtures\EnumWithIntAliases;
use BensonDevs\SuperchargedEnums\Tests\Fixtures\IntSampleEnum;
use BensonDevs\SuperchargedEnums\Tests\Fixtures\PartialCompositionEnum;
use BensonDevs\SuperchargedEnums\Tests\Fixtures\StringSampleEnum;

test('find resolves instance, scalar, and null', function () {
    expect(StringSampleEnum::find(null))->toBeNull();
    expect(StringSampleEnum::find(StringSampleEnum::NoShow))->toBe(StringSampleEnum::NoShow);
    expect(StringSampleEnum::find('no_show'))->toBe(StringSampleEnum::NoShow);
    expect(StringSampleEnum::find('missing'))->toBeNull();
});

test('find works for int-backed enums', function () {
    expect(IntSampleEnum::find(1))->toBe(IntSampleEnum::Alpha);
    expect(IntSampleEnum::find('2'))->toBe(IntSampleEnum::Beta);
});

test('findOrDefault falls back to first case', function () {
    expect(StringSampleEnum::findOrDefault('nope'))->toBe(StringSampleEnum::NoShow);
});

test('find resolves aliases when strict is false', function () {
    expect(EnumWithAliases::find('legacy_active'))->toBe(EnumWithAliases::Active);
    expect(EnumWithAliases::find('active'))->toBe(EnumWithAliases::Active);
});

test('find ignores aliases when strict is true', function () {
    expect(EnumWithAliases::find('legacy_active', strict: true))->toBeNull();
    expect(EnumWithAliases::find('active', strict: true))->toBe(EnumWithAliases::Active);
});

test('findOrDefault respects strict for aliases', function () {
    expect(EnumWithAliases::findOrDefault('legacy_active', strict: false))->toBe(EnumWithAliases::Active);
    expect(EnumWithAliases::findOrDefault('legacy_active', strict: true))->toBe(EnumWithAliases::First);
});

test('find resolves int-backed aliases with normalization', function () {
    expect(EnumWithIntAliases::find(99))->toBe(EnumWithIntAliases::Beta);
    expect(EnumWithIntAliases::find('99'))->toBe(EnumWithIntAliases::Beta);
    expect(EnumWithIntAliases::find(99, strict: true))->toBeNull();
});

test('EnumLookup and EnumEquality compose without the full EnumExtension', function () {
    expect(PartialCompositionEnum::find('left'))->toBe(PartialCompositionEnum::Left);
    expect(PartialCompositionEnum::Left->is('left'))->toBeTrue();
    expect(PartialCompositionEnum::Left->is('right'))->toBeFalse();
});
