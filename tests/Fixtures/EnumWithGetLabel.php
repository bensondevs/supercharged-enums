<?php

declare(strict_types=1);

namespace BensonDevs\SuperchargedEnums\Tests\Fixtures;

use BensonDevs\SuperchargedEnums\EnumExtension;

enum EnumWithGetLabel: string
{
    use EnumExtension;

    case One = 'one';

    case Two = 'two';

    public function getLabel(): string
    {
        return match ($this) {
            self::One => 'First',
            self::Two => 'Second',
        };
    }
}
