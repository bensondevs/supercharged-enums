<?php

declare(strict_types=1);

use BensonDevs\SuperchargedEnums\Common\DataSize\BinaryDataSizeUnit;

test('BinaryDataSizeUnit has eight cases and default Bit', function () {
    expect(BinaryDataSizeUnit::cases())->toHaveCount(8);
    expect(BinaryDataSizeUnit::default())->toBe(BinaryDataSizeUnit::Bit);
});

test('BinaryDataSizeUnit tryFrom and find resolve slugs', function () {
    expect(BinaryDataSizeUnit::tryFrom('mebibyte'))->toBe(BinaryDataSizeUnit::Mebibyte);
    expect(BinaryDataSizeUnit::tryFrom('kilobyte'))->toBeNull();
    expect(BinaryDataSizeUnit::find('kibibyte'))->toBe(BinaryDataSizeUnit::Kibibyte);
    expect(BinaryDataSizeUnit::find(null))->toBeNull();
});
