<?php

declare(strict_types=1);

namespace BensonDevs\SuperchargedEnums\Common\Platform;

use BensonDevs\SuperchargedEnums\EnumExtension;

/**
 * Common CPU architectures for builds and binaries. Case order defines {@see EnumExtension::default()}.
 *
 * {@see self::X86_64} is amd64. Backing values are lowercase English slugs.
 */
enum CpuArchitecture: string
{
    use EnumExtension;

    case X86_64 = 'x86_64';

    case Arm64 = 'arm64';

    case Arm = 'arm';

    case I686 = 'i686';
}
