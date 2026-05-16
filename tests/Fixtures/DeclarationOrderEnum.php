<?php

declare(strict_types=1);

namespace BensonDevs\SuperchargedEnums\Tests\Fixtures;

use BensonDevs\SuperchargedEnums\EnumExtension;

enum DeclarationOrderEnum: int
{
    use EnumExtension;

    case First = 2;

    case Second = 1;
}
