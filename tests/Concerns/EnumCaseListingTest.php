<?php

declare(strict_types=1);

use BensonDevs\SuperchargedEnums\Tests\Fixtures\StringSampleEnum;

test('names and values match cases', function () {
    expect(StringSampleEnum::names())->toBe(['NoShow', 'FirstOption']);
    expect(StringSampleEnum::values())->toBe(['no_show', 'first']);
});
