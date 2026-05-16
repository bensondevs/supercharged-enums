<?php

declare(strict_types=1);

namespace BensonDevs\SuperchargedEnums\Tests\Fixtures;

use BensonDevs\SuperchargedEnums\EnumExtension;

enum EnumWithGetDescription: string
{
    use EnumExtension;

    case Detailed = 'detailed';

    case Sparse = 'sparse';

    public function getDescription(): string
    {
        return match ($this) {
            self::Detailed => 'Full description',
            self::Sparse => '',
        };
    }
}
