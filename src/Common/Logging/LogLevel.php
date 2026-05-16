<?php

declare(strict_types=1);

namespace BensonDevs\SuperchargedEnums\Common\Logging;

use BensonDevs\SuperchargedEnums\EnumExtension;

/**
 * PSR-3 log levels from most to least verbose. Case order defines {@see EnumExtension::default()}.
 *
 * Backing values are lowercase English slugs matching PSR-3 level names.
 */
enum LogLevel: string
{
    use EnumExtension;

    case Debug = 'debug';

    case Info = 'info';

    case Notice = 'notice';

    case Warning = 'warning';

    case Error = 'error';

    case Critical = 'critical';

    case Alert = 'alert';

    case Emergency = 'emergency';
}
