<?php

declare(strict_types=1);

namespace BensonDevs\SuperchargedEnums\Tests\Fixtures;

use BensonDevs\SuperchargedEnums\EnumExtension;

enum IntSampleEnum: int
{
    use EnumExtension;

    case Alpha = 1;
    case Beta = 2;
}
