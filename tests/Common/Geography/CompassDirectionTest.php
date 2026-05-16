<?php

declare(strict_types=1);

use BensonDevs\SuperchargedEnums\Common\Geography\CompassDirection;

test('CompassDirection has eight cases and default North', function () {
    expect(CompassDirection::cases())->toHaveCount(8);
    expect(CompassDirection::default())->toBe(CompassDirection::North);
});

test('CompassDirection tryFrom and find resolve slugs', function () {
    expect(CompassDirection::tryFrom('ne'))->toBe(CompassDirection::NorthEast);
    expect(CompassDirection::tryFrom('sw'))->toBe(CompassDirection::SouthWest);
    expect(CompassDirection::tryFrom('north'))->toBeNull();
    expect(CompassDirection::find('nw'))->toBe(CompassDirection::NorthWest);
    expect(CompassDirection::find(null))->toBeNull();
});
