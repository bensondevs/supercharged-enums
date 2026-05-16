<?php

declare(strict_types=1);

namespace BensonDevs\SuperchargedEnums\Tests\Fixtures;

use BensonDevs\SuperchargedEnums\EnumExtension;

enum StringSampleEnum: string
{
    use EnumExtension;

    case NoShow = 'no_show';
    case FirstOption = 'first';
}
