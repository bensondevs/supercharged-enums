<?php

declare(strict_types=1);

use BensonDevs\SuperchargedEnums\Common\Time\Season;

test('Season has four cases and default Spring', function () {
    expect(Season::cases())->toHaveCount(4);
    expect(Season::default())->toBe(Season::Spring);
});

test('Season tryFrom and find resolve slugs', function () {
    expect(Season::tryFrom('summer'))->toBe(Season::Summer);
    expect(Season::tryFrom('autumn'))->toBe(Season::Autumn);
    expect(Season::tryFrom('fall'))->toBeNull();
    expect(Season::find('winter'))->toBe(Season::Winter);
    expect(Season::find(null))->toBeNull();
});
