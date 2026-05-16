<?php

declare(strict_types=1);

namespace BensonDevs\SuperchargedEnums\Tests\Fixtures;

use BensonDevs\SuperchargedEnums\EnumExtension;

enum EnumWithAliases: string
{
    use EnumExtension;

    case First = 'first';

    case Active = 'active';

    public function alias(): array
    {
        return match ($this) {
            self::Active => ['legacy_active'],
            default => [],
        };
    }
}
