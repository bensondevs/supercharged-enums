<?php

declare(strict_types=1);

namespace BensonDevs\SuperchargedEnums\Common\Geography;

use BensonDevs\SuperchargedEnums\EnumExtension;

/**
 * Earth's northern and southern hemispheres. Case order defines {@see EnumExtension::default()}.
 *
 * Backing values are lowercase English slugs.
 */
enum Hemisphere: string
{
    use EnumExtension;

    case Northern = 'northern';

    case Southern = 'southern';
}
