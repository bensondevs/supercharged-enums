<?php

declare(strict_types=1);

namespace BensonDevs\SuperchargedEnums\Common\Text;

use BensonDevs\SuperchargedEnums\EnumExtension;

/**
 * Text letter-casing styles. Case order defines {@see EnumExtension::default()}.
 *
 * Backing values are lowercase English slugs.
 */
enum TextCasing: string
{
    use EnumExtension;

    case Lower = 'lower';

    case Upper = 'upper';

    case Title = 'title';

    case Sentence = 'sentence';
}
