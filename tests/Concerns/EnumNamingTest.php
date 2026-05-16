<?php

declare(strict_types=1);

use BensonDevs\SuperchargedEnums\Tests\Fixtures\IntSampleEnum;
use BensonDevs\SuperchargedEnums\Tests\Fixtures\StringSampleEnum;

test('getKey returns backing value', function () {
    expect(StringSampleEnum::NoShow->getKey())->toBe('no_show');
    expect(IntSampleEnum::Alpha->getKey())->toBe(1);
});

test('getName handles PascalCase case names', function () {
    expect(StringSampleEnum::NoShow->getName())->toBe('No show');
    expect(StringSampleEnum::FirstOption->getName())->toBe('First option');
});
