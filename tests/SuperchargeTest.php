<?php

declare(strict_types=1);

use BensonDevs\SuperchargedEnums\Tests\Fixtures\PlainBackedEnum;
use BensonDevs\SuperchargedEnums\Tests\Fixtures\PlainBackedEnumWithAlias;
use BensonDevs\SuperchargedEnums\Tests\Fixtures\PlainBackedEnumWithCustomMethod;
use BensonDevs\SuperchargedEnums\Tests\Fixtures\StringSampleEnum;

use function BensonDevs\SuperchargedEnums\supercharge;

test('supercharge wraps a plain enum case for comparisons', function () {
    $sc = supercharge(PlainBackedEnum::Something);

    expect($sc->is('something'))->toBeTrue();
    expect($sc->is(PlainBackedEnum::Something))->toBeTrue();
    expect($sc->is('other'))->toBeFalse();
    expect($sc->is(null))->toBeFalse();
});

test('supercharge class overload exposes static lookup helpers', function () {
    expect(supercharge(PlainBackedEnum::class)->find('something'))->toBe(PlainBackedEnum::Something);
    expect(supercharge(PlainBackedEnum::class)->find('missing'))->toBeNull();
    expect(supercharge(PlainBackedEnum::class)->findOrDefault('missing'))->toBe(PlainBackedEnum::Something);
});

test('supercharge exposes naming and select maps for plain enums', function () {
    $sc = supercharge(PlainBackedEnum::Something);

    expect($sc->getKey())->toBe('something');
    expect($sc->getName())->toBe('Something');
    expect($sc->name)->toBe('Something');
    expect($sc->value)->toBe('something');
    expect(supercharge(PlainBackedEnum::class)->options())->toBe([
        'something' => 'Something',
        'other' => 'Other',
    ]);
});

test('supercharge supports ordering and navigation on plain enums', function () {
    $sc = supercharge(PlainBackedEnum::Something);

    expect($sc->isBefore('other'))->toBeTrue();
    expect($sc->isAfter('other'))->toBeFalse();
    expect($sc->next())->toBe(PlainBackedEnum::Other);
    expect($sc->isFirst())->toBeTrue();
    expect($sc->isLast())->toBeFalse();
});

test('supercharge resolves aliases on plain enums with alias method', function () {
    expect(supercharge(PlainBackedEnumWithAlias::class)->find('legacy_active'))->toBe(PlainBackedEnumWithAlias::Active);
    expect(supercharge(PlainBackedEnumWithAlias::Active)->is('legacy_active'))->toBeTrue();
});

test('supercharge unwrap returns the native enum case', function () {
    expect(supercharge(PlainBackedEnum::Something)->unwrap())->toBe(PlainBackedEnum::Something);
});

test('supercharge forwards custom enum methods via __call', function () {
    expect(supercharge(PlainBackedEnumWithCustomMethod::Alpha)->customLabel())->toBe('Custom Alpha');
});

test('supercharge string cast uses backing value for string backed enums', function () {
    expect((string) supercharge(PlainBackedEnum::Something))->toBe('something');
});

test('supercharge rejects pure unit enums', function () {
    supercharge(UnitPlainEnum::class);
})->throws(InvalidArgumentException::class);

enum UnitPlainEnum
{
    case Only;
}

test('supercharge still works for enums that already use EnumExtension', function () {
    $sc = supercharge(StringSampleEnum::NoShow);

    expect($sc->is('no_show'))->toBeTrue();
    expect($sc->unwrap())->toBe(StringSampleEnum::NoShow);
    expect(supercharge(StringSampleEnum::class)->find('no_show'))->toBe(StringSampleEnum::NoShow);
});

test('supercharge type exposes core static helpers', function () {
    $type = supercharge(PlainBackedEnum::class);

    expect($type->names())->toBe(['Something', 'Other']);
    expect($type->values())->toBe(['something', 'other']);
    expect($type->default())->toBe(PlainBackedEnum::Something);
    expect($type->random())->toBeInstanceOf(PlainBackedEnum::class);
    expect($type->min('other', PlainBackedEnum::Something))->toBe(PlainBackedEnum::Something);
    expect($type->max('other', PlainBackedEnum::Something))->toBe(PlainBackedEnum::Other);
});
