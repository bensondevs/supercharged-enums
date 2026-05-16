<?php

declare(strict_types=1);

namespace BensonDevs\SuperchargedEnums\Tests\Fixtures;

use BensonDevs\SuperchargedEnums\EnumExtension;

enum EnumWithIntAliases: int
{
    use EnumExtension;

    case Alpha = 1;

    case Beta = 2;

    public function alias(): array
    {
        return match ($this) {
            self::Beta => [99],
            default => [],
        };
    }
}
