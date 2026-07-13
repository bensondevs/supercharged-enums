<?php

declare(strict_types=1);

namespace BensonDevs\SuperchargedEnums\Support;

use BackedEnum;
use UnitEnum;

final class BackedEnumComparisons
{
    /**
     * @param  class-string<BackedEnum>  $enumClass
     */
    public static function is(string $enumClass, BackedEnum $case, UnitEnum | string | int | null $other): bool
    {
        $resolved = BackedEnumLookup::find($enumClass, $other);

        return $resolved !== null && $case === $resolved;
    }

    /**
     * @param  class-string<BackedEnum>  $enumClass
     * @param  array<int, UnitEnum|string|int|null>  $enums
     */
    public static function isIn(string $enumClass, BackedEnum $case, array $enums, bool $strict = false): bool
    {
        $normalizedEnums = [];
        foreach ($enums as $enum) {
            $resolved = BackedEnumLookup::find($enumClass, $enum);
            if ($resolved !== null) {
                $normalizedEnums[] = $resolved;
            }
        }

        return in_array($case, $normalizedEnums, strict: $strict);
    }

    /**
     * @param  class-string<BackedEnum>  $enumClass
     */
    public static function isNot(string $enumClass, BackedEnum $case, UnitEnum | string | int $other): bool
    {
        return ! self::is($enumClass, $case, $other);
    }

    /**
     * @param  class-string<BackedEnum>  $enumClass
     * @param  array<int, UnitEnum|string|int|null>  $enums
     */
    public static function isNotIn(string $enumClass, BackedEnum $case, array $enums, bool $strict = false): bool
    {
        return ! self::isIn($enumClass, $case, $enums, $strict);
    }

    /**
     * @param  class-string<BackedEnum>  $enumClass
     */
    public static function compareTo(string $enumClass, BackedEnum $case, UnitEnum | string | int | null $other): ?int
    {
        $resolved = BackedEnumLookup::find($enumClass, $other);

        if ($resolved === null) {
            return null;
        }

        return self::caseIndex($enumClass, $case) <=> self::caseIndex($enumClass, $resolved);
    }

    /**
     * @param  class-string<BackedEnum>  $enumClass
     */
    public static function isAfter(string $enumClass, BackedEnum $case, UnitEnum | string | int | null $other): bool
    {
        return self::compareTo($enumClass, $case, $other) === 1;
    }

    /**
     * @param  class-string<BackedEnum>  $enumClass
     */
    public static function isAfterOrEqual(string $enumClass, BackedEnum $case, UnitEnum | string | int | null $other): bool
    {
        $comparison = self::compareTo($enumClass, $case, $other);

        return $comparison !== null && $comparison >= 0;
    }

    /**
     * @param  class-string<BackedEnum>  $enumClass
     */
    public static function isBefore(string $enumClass, BackedEnum $case, UnitEnum | string | int | null $other): bool
    {
        return self::compareTo($enumClass, $case, $other) === -1;
    }

    /**
     * @param  class-string<BackedEnum>  $enumClass
     */
    public static function isBeforeOrEqual(string $enumClass, BackedEnum $case, UnitEnum | string | int | null $other): bool
    {
        $comparison = self::compareTo($enumClass, $case, $other);

        return $comparison !== null && $comparison <= 0;
    }

    /**
     * @param  class-string<BackedEnum>  $enumClass
     */
    public static function isBetween(
        string $enumClass,
        BackedEnum $case,
        UnitEnum | string | int | null $start,
        UnitEnum | string | int | null $end,
        bool $includeStart = true,
        bool $includeEnd = true,
    ): bool {
        $startResolved = BackedEnumLookup::find($enumClass, $start);
        $endResolved = BackedEnumLookup::find($enumClass, $end);

        if ($startResolved === null || $endResolved === null) {
            return false;
        }

        $index = self::caseIndex($enumClass, $case);
        $startIndex = self::caseIndex($enumClass, $startResolved);
        $endIndex = self::caseIndex($enumClass, $endResolved);

        if ($startIndex > $endIndex) {
            [$startIndex, $endIndex] = [$endIndex, $startIndex];
            [$includeStart, $includeEnd] = [$includeEnd, $includeStart];
        }

        $afterStart = $includeStart ? $index >= $startIndex : $index > $startIndex;
        $beforeEnd = $includeEnd ? $index <= $endIndex : $index < $endIndex;

        return $afterStart && $beforeEnd;
    }

    /**
     * @param  class-string<BackedEnum>  $enumClass
     */
    public static function isFirst(string $enumClass, BackedEnum $case): bool
    {
        return self::caseIndex($enumClass, $case) === 0;
    }

    /**
     * @param  class-string<BackedEnum>  $enumClass
     */
    public static function isLast(string $enumClass, BackedEnum $case): bool
    {
        return self::caseIndex($enumClass, $case) === count($enumClass::cases()) - 1;
    }

    /**
     * @template T of BackedEnum
     *
     * @param  class-string<T>  $enumClass
     * @return T|null
     */
    public static function next(string $enumClass, BackedEnum $case, bool $wrap = false): ?BackedEnum
    {
        $cases = $enumClass::cases();
        $index = self::caseIndex($enumClass, $case);

        if ($index < count($cases) - 1) {
            return $cases[$index + 1];
        }

        return $wrap ? $cases[0] : null;
    }

    /**
     * @template T of BackedEnum
     *
     * @param  class-string<T>  $enumClass
     * @return T|null
     */
    public static function previous(string $enumClass, BackedEnum $case, bool $wrap = false): ?BackedEnum
    {
        $cases = $enumClass::cases();
        $index = self::caseIndex($enumClass, $case);

        if ($index > 0) {
            return $cases[$index - 1];
        }

        return $wrap ? $cases[count($cases) - 1] : null;
    }

    /**
     * @param  class-string<BackedEnum>  $enumClass
     */
    public static function diff(string $enumClass, BackedEnum $case, UnitEnum | string | int | null $other): ?int
    {
        $resolved = BackedEnumLookup::find($enumClass, $other);

        if ($resolved === null) {
            return null;
        }

        return self::caseIndex($enumClass, $case) - self::caseIndex($enumClass, $resolved);
    }

    /**
     * @template T of BackedEnum
     *
     * @param  class-string<T>  $enumClass
     * @param  array<int, UnitEnum|string|int|null>  $enums
     * @return T|null
     */
    public static function min(string $enumClass, array $enums): ?BackedEnum
    {
        return self::extremeCase($enumClass, $enums, lowest: true);
    }

    /**
     * @template T of BackedEnum
     *
     * @param  class-string<T>  $enumClass
     * @param  array<int, UnitEnum|string|int|null>  $enums
     * @return T|null
     */
    public static function max(string $enumClass, array $enums): ?BackedEnum
    {
        return self::extremeCase($enumClass, $enums, lowest: false);
    }

    /**
     * @param  class-string<BackedEnum>  $enumClass
     */
    public static function caseIndex(string $enumClass, BackedEnum $case): int
    {
        $index = array_search($case, $enumClass::cases(), true);

        assert($index !== false);

        return $index;
    }

    /**
     * @template T of BackedEnum
     *
     * @param  class-string<T>  $enumClass
     * @param  array<int, UnitEnum|string|int|null>  $enums
     * @return T|null
     */
    private static function extremeCase(string $enumClass, array $enums, bool $lowest): ?BackedEnum
    {
        $resolved = [];

        foreach ($enums as $enum) {
            $found = BackedEnumLookup::find($enumClass, $enum);

            if ($found !== null) {
                $resolved[] = $found;
            }
        }

        if ($resolved === []) {
            return null;
        }

        $extreme = $resolved[0];

        foreach (array_slice($resolved, 1) as $resolvedCase) {
            if ($lowest) {
                if (self::caseIndex($enumClass, $resolvedCase) < self::caseIndex($enumClass, $extreme)) {
                    $extreme = $resolvedCase;
                }
            } elseif (self::caseIndex($enumClass, $resolvedCase) > self::caseIndex($enumClass, $extreme)) {
                $extreme = $resolvedCase;
            }
        }

        return $extreme;
    }
}
