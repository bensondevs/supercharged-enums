<?php

declare(strict_types=1);

namespace BensonDevs\SuperchargedEnums\Common\Application;

use BensonDevs\SuperchargedEnums\EnumExtension;

/**
 * Typical application deployment environments. Case order defines {@see EnumExtension::default()}.
 *
 * Backing values are lowercase English slugs.
 */
enum DeploymentEnvironment: string
{
    use EnumExtension;

    case Local = 'local';

    case Development = 'development';

    case Staging = 'staging';

    case Production = 'production';

    case Testing = 'testing';
}
