<?php

declare(strict_types=1);

use BensonDevs\SuperchargedEnums\Tests\Fixtures\EnumWithGetDescription;
use BensonDevs\SuperchargedEnums\Tests\Fixtures\EnumWithGetLabel;
use BensonDevs\SuperchargedEnums\Tests\Fixtures\EnumWithLabelMethod;
use BensonDevs\SuperchargedEnums\Tests\Fixtures\EnumWithSelectables;
use BensonDevs\SuperchargedEnums\Tests\Fixtures\EnumWithSelectablesAndUnselectables;
use BensonDevs\SuperchargedEnums\Tests\Fixtures\EnumWithUnselectables;
use BensonDevs\SuperchargedEnums\Tests\Fixtures\StringSampleEnum;
use Illuminate\Support\Collection;

test('options prefers getLabel when present', function () {
    expect(EnumWithGetLabel::options())->toBe([
        'one' => 'First',
        'two' => 'Second',
    ]);
});

test('options prefers label() over getLabel when both patterns exist', function () {
    expect(EnumWithLabelMethod::options())->toBe([
        'only' => 'From label()',
    ]);
});

test('options and asSelectDescriptions respect unselectables', function () {
    expect(EnumWithUnselectables::options())->toBe([
        'visible' => 'Visible',
        'also_visible' => 'Also visible',
    ]);
    expect(EnumWithUnselectables::asSelectDescriptions())->toBe([
        'visible' => 'Visible',
        'also_visible' => 'AlsoVisible',
    ]);
});

test('options respects selectables in declaration order', function () {
    expect(EnumWithSelectables::options())->toBe([
        'beta' => 'Beta',
        'gamma' => 'Gamma',
    ]);
});

test('selectables supersedes unselectables when both are defined', function () {
    expect(EnumWithSelectablesAndUnselectables::options())->toBe([
        'draft' => 'Draft',
        'published' => 'Published',
    ]);
    expect(EnumWithSelectablesAndUnselectables::asSelectDescriptions())->toBe([
        'draft' => 'Draft',
        'published' => 'Published',
    ]);
});

test('asSelectDescriptions prefers getDescription', function () {
    expect(EnumWithGetDescription::asSelectDescriptions())->toBe([
        'detailed' => 'Full description',
        'sparse' => '',
    ]);
});

test('asSelectDescriptions falls back to getLabel when getDescription is absent', function () {
    expect(EnumWithGetLabel::asSelectDescriptions())->toBe([
        'one' => 'First',
        'two' => 'Second',
    ]);
});

test('all returns all cases when no filters are defined', function () {
    expect(StringSampleEnum::all())->toBe(StringSampleEnum::cases());
    expect(StringSampleEnum::all())->toBe(StringSampleEnum::filteredCases());
});

test('all respects selectables in declaration order', function () {
    expect(EnumWithSelectables::all())->toBe([
        EnumWithSelectables::Beta,
        EnumWithSelectables::Gamma,
    ]);
    expect(EnumWithSelectables::all())->toBe(EnumWithSelectables::filteredCases());
});

test('all respects unselectables', function () {
    expect(EnumWithUnselectables::all())->toBe([
        EnumWithUnselectables::Visible,
        EnumWithUnselectables::AlsoVisible,
    ]);
    expect(EnumWithUnselectables::all())->toBe(EnumWithUnselectables::filteredCases());
});

test('collect returns a collection of filtered cases', function () {
    $collection = StringSampleEnum::collect();

    expect($collection)->toBeInstanceOf(Collection::class);
    expect($collection->all())->toBe(StringSampleEnum::all());
});
