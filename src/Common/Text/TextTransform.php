<?php

declare(strict_types=1);

namespace BensonDevs\SuperchargedEnums\Common\Text;

use BensonDevs\SuperchargedEnums\EnumExtension;

/**
 * Identifier / string naming transforms. Case order defines {@see EnumExtension::default()}.
 *
 * Backing values are lowercase English slugs.
 */
enum TextTransform: string
{
    use EnumExtension;

    case None = 'none';

    case Snake = 'snake';

    case Kebab = 'kebab';

    case Camel = 'camel';

    case Pascal = 'pascal';
}
