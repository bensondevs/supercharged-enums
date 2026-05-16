<?php

declare(strict_types=1);

use BensonDevs\SuperchargedEnums\Common\Application\DeploymentEnvironment;

test('DeploymentEnvironment has five cases and default Local', function () {
    expect(DeploymentEnvironment::cases())->toHaveCount(5);
    expect(DeploymentEnvironment::default())->toBe(DeploymentEnvironment::Local);
});

test('DeploymentEnvironment tryFrom and find resolve slugs', function () {
    expect(DeploymentEnvironment::tryFrom('production'))->toBe(DeploymentEnvironment::Production);
    expect(DeploymentEnvironment::tryFrom('staging'))->toBe(DeploymentEnvironment::Staging);
    expect(DeploymentEnvironment::tryFrom('qa'))->toBeNull();
    expect(DeploymentEnvironment::find('testing'))->toBe(DeploymentEnvironment::Testing);
    expect(DeploymentEnvironment::find(null))->toBeNull();
});
