<?php

declare(strict_types=1);

namespace BensonDevs\SuperchargedEnums\Tests\Fixtures;

enum PlainBackedEnumWithAlias: string
{
    case Active = 'active';
    case First = 'first';

    public function alias(): array
    {
        return match ($this) {
            self::Active => ['legacy_active'],
            default => [],
        };
    }
}
