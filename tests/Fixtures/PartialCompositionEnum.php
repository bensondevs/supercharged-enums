<?php

declare(strict_types=1);

namespace BensonDevs\SuperchargedEnums\Tests\Fixtures;

use BensonDevs\SuperchargedEnums\Concerns\EnumComparisons;
use BensonDevs\SuperchargedEnums\Concerns\EnumLookup;

enum PartialCompositionEnum: string
{
    use EnumComparisons;
    use EnumLookup;

    case Left = 'left';
    case Right = 'right';
}
