<?php

declare(strict_types=1);

use BensonDevs\SuperchargedEnums\Common\Device\DeviceType;

test('DeviceType has eight cases and default Mobile', function () {
    expect(DeviceType::cases())->toHaveCount(8);
    expect(DeviceType::default())->toBe(DeviceType::Mobile);
});

test('DeviceType tryFrom and find resolve slugs', function () {
    expect(DeviceType::tryFrom('console'))->toBe(DeviceType::Console);
    expect(DeviceType::tryFrom('embedded'))->toBe(DeviceType::Embedded);
    expect(DeviceType::tryFrom('watch'))->toBeNull();
    expect(DeviceType::find('wearable'))->toBe(DeviceType::Wearable);
    expect(DeviceType::find(null))->toBeNull();
});
