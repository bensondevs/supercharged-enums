<?php

declare(strict_types=1);

use BensonDevs\SuperchargedEnums\Common\Geography\Hemisphere;

test('Hemisphere has two cases and default Northern', function () {
    expect(Hemisphere::cases())->toHaveCount(2);
    expect(Hemisphere::default())->toBe(Hemisphere::Northern);
});

test('Hemisphere tryFrom and find resolve slugs', function () {
    expect(Hemisphere::tryFrom('southern'))->toBe(Hemisphere::Southern);
    expect(Hemisphere::tryFrom('eastern'))->toBeNull();
    expect(Hemisphere::find('northern'))->toBe(Hemisphere::Northern);
    expect(Hemisphere::find(null))->toBeNull();
});
