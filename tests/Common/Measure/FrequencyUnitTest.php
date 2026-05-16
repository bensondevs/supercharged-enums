<?php

declare(strict_types=1);

use BensonDevs\SuperchargedEnums\Common\Measure\FrequencyUnit;

test('FrequencyUnit has four cases and default Hertz', function () {
    expect(FrequencyUnit::cases())->toHaveCount(4);
    expect(FrequencyUnit::default())->toBe(FrequencyUnit::Hertz);
});

test('FrequencyUnit tryFrom and find resolve slugs', function () {
    expect(FrequencyUnit::tryFrom('megahertz'))->toBe(FrequencyUnit::Megahertz);
    expect(FrequencyUnit::tryFrom('gigahertz'))->toBe(FrequencyUnit::Gigahertz);
    expect(FrequencyUnit::tryFrom('terahertz'))->toBeNull();
    expect(FrequencyUnit::find('kilohertz'))->toBe(FrequencyUnit::Kilohertz);
    expect(FrequencyUnit::find(null))->toBeNull();
});
