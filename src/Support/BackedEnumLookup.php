<?php

declare(strict_types=1);

namespace BensonDevs\SuperchargedEnums\Support;

use BackedEnum;
use UnitEnum;

final class BackedEnumLookup
{
    /**
     * @template T of BackedEnum
     *
     * @param  class-string<T>  $enumClass
     * @param  T|string|int|null  $key
     * @return T|null
     */
    public static function find(string $enumClass, UnitEnum | string | int | null $key, bool $strict = false): ?BackedEnum
    {
        if ($key instanceof UnitEnum) {
            if ($key instanceof $enumClass) {
                /** @var T $key */
                return $key;
            }

            return null;
        }

        if (is_null($key)) {
            return null;
        }

        $normalized = self::normalizeScalarKey($enumClass, $key);

        if ($normalized === null) {
            return null;
        }

        /** @var T|null $resolved */
        $resolved = $enumClass::tryFrom($normalized);

        if ($resolved !== null) {
            return $resolved;
        }

        if ($strict || ! self::supportsAliases($enumClass)) {
            return null;
        }

        return self::resolveViaAliases($enumClass, $normalized);
    }

    /**
     * @template T of BackedEnum
     *
     * @param  class-string<T>  $enumClass
     * @param  T|string|int|null  $key
     * @return T
     */
    public static function findOrDefault(string $enumClass, UnitEnum | string | int | null $key, bool $strict = false): BackedEnum
    {
        return self::find($enumClass, $key, $strict) ?? BackedEnumCore::default($enumClass);
    }

    /**
     * @param  class-string<BackedEnum>  $enumClass
     */
    public static function supportsAliases(string $enumClass): bool
    {
        return method_exists($enumClass, 'alias');
    }

    /**
     * @template T of BackedEnum
     *
     * @param  class-string<T>  $enumClass
     * @return T|null
     */
    private static function resolveViaAliases(string $enumClass, string | int $normalized): ?BackedEnum
    {
        foreach ($enumClass::cases() as $case) {
            if (! method_exists($case, 'alias')) {
                continue;
            }

            /** @var list<string|int> $aliases */
            $aliases = $case->alias();

            foreach ($aliases as $alias) {
                $aliasNormalized = self::normalizeScalarKey($enumClass, $alias);

                if ($aliasNormalized === null || $aliasNormalized !== $normalized) {
                    continue;
                }

                return $case;
            }
        }

        return null;
    }

    /**
     * @param  class-string<BackedEnum>  $enumClass
     */
    private static function normalizeScalarKey(string $enumClass, string | int $key): string | int | null
    {
        $backingType = (new \ReflectionEnum($enumClass))->getBackingType();

        if ($backingType === null) {
            return null;
        }

        return match ($backingType->getName()) {
            'int' => match (true) {
                is_int($key) => $key,
                filter_var($key, FILTER_VALIDATE_INT) !== false => (int) $key,
                default => null,
            },
            'string' => is_int($key) ? (string) $key : $key,
            default => null,
        };
    }
}
