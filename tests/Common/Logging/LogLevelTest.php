<?php

declare(strict_types=1);

use BensonDevs\SuperchargedEnums\Common\Logging\LogLevel;

test('LogLevel has eight cases and default Debug', function () {
    expect(LogLevel::cases())->toHaveCount(8);
    expect(LogLevel::default())->toBe(LogLevel::Debug);
});

test('LogLevel tryFrom and find resolve slugs', function () {
    expect(LogLevel::tryFrom('warning'))->toBe(LogLevel::Warning);
    expect(LogLevel::tryFrom('emergency'))->toBe(LogLevel::Emergency);
    expect(LogLevel::tryFrom('trace'))->toBeNull();
    expect(LogLevel::find('critical'))->toBe(LogLevel::Critical);
    expect(LogLevel::find(null))->toBeNull();
});
