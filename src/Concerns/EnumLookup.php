<?php

declare(strict_types=1);

namespace BensonDevs\SuperchargedEnums\Concerns;

/**
 * Optional per-case `alias(): array<int|string>` alternate keys matching the backing type.
 *
 * Duplicate aliases among cases are undefined; `cases()` iteration order decides the winner.
 */
trait EnumLookup
{
    public static function find(self|string|int|null $key, bool $strict = false): ?static
    {
        if ($key instanceof self) {
            return $key;
        }

        if (is_null($key)) {
            return null;
        }

        $normalized = self::normalizeScalarKey($key);

        if ($normalized === null) {
            return null;
        }

        $resolved = self::tryFrom($normalized);

        if ($resolved !== null) {
            return $resolved;
        }

        if ($strict || ! self::supportsAliases()) {
            return null;
        }

        return self::resolveViaAliases($normalized);
    }

    public static function findOrDefault(self|string|int|null $key, bool $strict = false): static
    {
        return self::find($key, $strict) ?? self::default();
    }

    private static function supportsAliases(): bool
    {
        return method_exists(static::class, 'alias');
    }

    /**
     * @param  string|int  $normalized  Backing-compatible key produced by normalizeScalarKey
     */
    private static function resolveViaAliases(string|int $normalized): ?static
    {
        foreach (self::cases() as $case) {
            foreach ($case->alias() as $alias) {
                if (! is_string($alias) && ! is_int($alias)) {
                    continue;
                }

                $aliasNormalized = self::normalizeScalarKey($alias);

                if ($aliasNormalized === null || $aliasNormalized !== $normalized) {
                    continue;
                }

                return $case;
            }
        }

        return null;
    }

    /**
     * @return string|int|null Null when the key cannot be coerced to the enum's backing type.
     */
    private static function normalizeScalarKey(string|int $key): string|int|null
    {
        $backingType = (new \ReflectionEnum(static::class))->getBackingType();

        if ($backingType === null) {
            return null;
        }

        return match ($backingType->getName()) {
            'int' => match (true) {
                is_int($key) => $key,
                is_string($key) && filter_var($key, FILTER_VALIDATE_INT) !== false => (int) $key,
                default => null,
            },
            'string' => is_string($key) ? $key : (string) $key,
            default => null,
        };
    }
}
