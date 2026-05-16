<?php

declare(strict_types=1);

use BensonDevs\SuperchargedEnums\Tests\Fixtures\IntSampleEnum;
use BensonDevs\SuperchargedEnums\Tests\Fixtures\StringSampleEnum;

test('default is the first declared case', function () {
    expect(StringSampleEnum::default())->toBe(StringSampleEnum::NoShow);
    expect(IntSampleEnum::default())->toBe(IntSampleEnum::Alpha);
});

test('random returns a declared case', function () {
    expect(StringSampleEnum::random())->toBeInstanceOf(StringSampleEnum::class);
});
