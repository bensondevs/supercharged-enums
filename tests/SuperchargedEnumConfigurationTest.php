<?php

declare(strict_types=1);

use BensonDevs\SuperchargedEnums\SuperchargedEnumConfiguration;
use BensonDevs\SuperchargedEnums\SuperchargedEnumType;
use BensonDevs\SuperchargedEnums\Tests\Fixtures\EnumWithSelectables;
use BensonDevs\SuperchargedEnums\Tests\Fixtures\PlainBackedEnum;
use BensonDevs\SuperchargedEnums\Tests\Fixtures\PlainConfigurableEnum;

use function BensonDevs\SuperchargedEnums\supercharge;

beforeEach(function () {
    SuperchargedEnumConfiguration::reset();
});

test('configureUsing overrides default for plain enums', function () {
    supercharge(PlainConfigurableEnum::class)->configureUsing(
        fn (SuperchargedEnumType $type) => $type->setDefault(PlainConfigurableEnum::Anything),
    );

    $type = supercharge(PlainConfigurableEnum::class);

    expect($type->default())->toBe(PlainConfigurableEnum::Anything);
    expect($type->findOrDefault('missing'))->toBe(PlainConfigurableEnum::Anything);
});

test('configureUsing filters selectables for plain enums', function () {
    supercharge(PlainConfigurableEnum::class)->configureUsing(
        fn (SuperchargedEnumType $type) => $type->setSelectables([
            PlainConfigurableEnum::FirstDefault,
            'second',
            'anything',
        ]),
    );

    $type = supercharge(PlainConfigurableEnum::class);

    expect($type->all())->toBe([
        PlainConfigurableEnum::FirstDefault,
        PlainConfigurableEnum::Second,
        PlainConfigurableEnum::Anything,
    ]);
    expect($type->options())->toBe([
        'first' => 'FirstDefault',
        'second' => 'Second',
        'anything' => 'Anything',
    ]);
    expect($type->collect())->toBeInstanceOf(Illuminate\Support\Collection::class);
    expect($type->collect()->all())->toBe($type->all());
});

test('configured selectables beat configured unselectables', function () {
    supercharge(PlainConfigurableEnum::class)->configureUsing(
        fn (SuperchargedEnumType $type) => $type
            ->setSelectables([PlainConfigurableEnum::Second])
            ->setUnselectables([PlainConfigurableEnum::FirstDefault]),
    );

    expect(supercharge(PlainConfigurableEnum::class)->all())->toBe([
        PlainConfigurableEnum::Second,
    ]);
});

test('runtime configuration overrides native enum methods', function () {
    supercharge(EnumWithSelectables::class)->configureUsing(
        fn (SuperchargedEnumType $type) => $type
            ->setDefault(EnumWithSelectables::Alpha)
            ->setSelectables([EnumWithSelectables::Alpha]),
    );

    $type = supercharge(EnumWithSelectables::class);

    expect($type->default())->toBe(EnumWithSelectables::Alpha);
    expect($type->all())->toBe([EnumWithSelectables::Alpha]);
    expect($type->options())->toBe(['alpha' => 'Alpha']);
});

test('setDefault rejects cases from another enum', function () {
    supercharge(PlainConfigurableEnum::class)->configureUsing(
        fn (SuperchargedEnumType $type) => $type->setDefault(PlainBackedEnum::Something),
    );
})->throws(InvalidArgumentException::class);

test('configuration reset clears runtime overrides', function () {
    supercharge(PlainConfigurableEnum::class)->configureUsing(
        fn (SuperchargedEnumType $type) => $type->setDefault(PlainConfigurableEnum::Anything),
    );

    SuperchargedEnumConfiguration::reset();

    expect(supercharge(PlainConfigurableEnum::class)->default())->toBe(PlainConfigurableEnum::FirstDefault);
});
