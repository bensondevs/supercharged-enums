<?php

declare(strict_types=1);

use BensonDevs\SuperchargedEnums\Common\DataSize\DecimalDataSizeUnit;

test('DecimalDataSizeUnit has eight cases and default Bit', function () {
    expect(DecimalDataSizeUnit::cases())->toHaveCount(8);
    expect(DecimalDataSizeUnit::default())->toBe(DecimalDataSizeUnit::Bit);
});

test('DecimalDataSizeUnit tryFrom and find resolve slugs', function () {
    expect(DecimalDataSizeUnit::tryFrom('byte'))->toBe(DecimalDataSizeUnit::Byte);
    expect(DecimalDataSizeUnit::tryFrom('megabyte'))->toBe(DecimalDataSizeUnit::Megabyte);
    expect(DecimalDataSizeUnit::tryFrom('kibibyte'))->toBeNull();
    expect(DecimalDataSizeUnit::find('exabyte'))->toBe(DecimalDataSizeUnit::Exabyte);
    expect(DecimalDataSizeUnit::find(null))->toBeNull();
});
