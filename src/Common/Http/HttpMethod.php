<?php

declare(strict_types=1);

namespace BensonDevs\SuperchargedEnums\Common\Http;

use BensonDevs\SuperchargedEnums\EnumExtension;

/**
 * Common HTTP request methods. Case order defines {@see EnumExtension::default()}.
 *
 * Backing values are lowercase English slugs (HTTP verbs).
 */
enum HttpMethod: string
{
    use EnumExtension;

    case Get = 'get';

    case Head = 'head';

    case Post = 'post';

    case Put = 'put';

    case Patch = 'patch';

    case Delete = 'delete';

    case Options = 'options';

    case Trace = 'trace';

    case Connect = 'connect';
}
