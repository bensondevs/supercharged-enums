<?php

declare(strict_types=1);

use BensonDevs\SuperchargedEnums\Tests\Fixtures\EnumWithGetDescription;
use BensonDevs\SuperchargedEnums\Tests\Fixtures\EnumWithGetLabel;
use BensonDevs\SuperchargedEnums\Tests\Fixtures\EnumWithLabelMethod;
use BensonDevs\SuperchargedEnums\Tests\Fixtures\EnumWithSelectables;
use BensonDevs\SuperchargedEnums\Tests\Fixtures\EnumWithSelectablesAndUnselectables;
use BensonDevs\SuperchargedEnums\Tests\Fixtures\EnumWithUnselectables;

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
