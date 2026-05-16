<?php

declare(strict_types=1);

use BensonDevs\SuperchargedEnums\Common\Platform\CpuArchitecture;

test('CpuArchitecture has four cases and default X86_64', function () {
    expect(CpuArchitecture::cases())->toHaveCount(4);
    expect(CpuArchitecture::default())->toBe(CpuArchitecture::X86_64);
});

test('CpuArchitecture tryFrom and find resolve slugs', function () {
    expect(CpuArchitecture::tryFrom('arm64'))->toBe(CpuArchitecture::Arm64);
    expect(CpuArchitecture::tryFrom('i686'))->toBe(CpuArchitecture::I686);
    expect(CpuArchitecture::tryFrom('riscv64'))->toBeNull();
    expect(CpuArchitecture::find('arm'))->toBe(CpuArchitecture::Arm);
    expect(CpuArchitecture::find(null))->toBeNull();
});
