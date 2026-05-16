<?php

declare(strict_types=1);

use BensonDevs\SuperchargedEnums\Common\Platform\OperatingSystemFamily;

test('OperatingSystemFamily has four cases and default Linux', function () {
    expect(OperatingSystemFamily::cases())->toHaveCount(4);
    expect(OperatingSystemFamily::default())->toBe(OperatingSystemFamily::Linux);
});

test('OperatingSystemFamily tryFrom and find resolve slugs', function () {
    expect(OperatingSystemFamily::tryFrom('windows'))->toBe(OperatingSystemFamily::Windows);
    expect(OperatingSystemFamily::tryFrom('macos'))->toBe(OperatingSystemFamily::Macos);
    expect(OperatingSystemFamily::tryFrom('solaris'))->toBeNull();
    expect(OperatingSystemFamily::find('bsd'))->toBe(OperatingSystemFamily::Bsd);
    expect(OperatingSystemFamily::find(null))->toBeNull();
});
