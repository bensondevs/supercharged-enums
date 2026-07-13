<?php

declare(strict_types=1);

namespace BensonDevs\SuperchargedEnums\Concerns;

use BensonDevs\SuperchargedEnums\Support\BackedEnumLookup;

/**
 * Optional per-case `alias(): array<int|string>` alternate keys matching the backing type.
 *
 * Duplicate aliases among cases are undefined; `cases()` iteration order decides the winner.
 */
trait EnumLookup
{
    public static function find(self | string | int | null $key, bool $strict = false): ?static
    {
        /** @var static|null */
        return BackedEnumLookup::find(static::class, $key, $strict);
    }

    public static function findOrDefault(self | string | int | null $key, bool $strict = false): static
    {
        return BackedEnumLookup::findOrDefault(static::class, $key, $strict);
    }
}
