<?php

declare(strict_types=1);

namespace BensonDevs\SuperchargedEnums\Tests\Fixtures;

use BensonDevs\SuperchargedEnums\EnumExtension;

enum EnumWithLabelMethod: string
{
    use EnumExtension;

    case Only = 'only';

    public function label(): string
    {
        return 'From label()';
    }
}
