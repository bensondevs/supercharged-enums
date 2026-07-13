<?php

declare(strict_types=1);

namespace BensonDevs\SuperchargedEnums\Concerns;

use BensonDevs\SuperchargedEnums\Support\BackedEnumComparisons;

/**
 * Equality and declaration-order comparisons.
 *
 * Ordering uses {@see static::cases()} iteration order, not backing values.
 * Requires {@see EnumLookup} for `find()` resolution of scalar operands.
 */
trait EnumComparisons
{
    public function is(self | string | int | null $enum): bool
    {
        return BackedEnumComparisons::is(static::class, $this, $enum);
    }

    public function isIn(array $enums, bool $strict = false): bool
    {
        return BackedEnumComparisons::isIn(static::class, $this, $enums, $strict);
    }

    public function isNot(self | string | int $enum): bool
    {
        return BackedEnumComparisons::isNot(static::class, $this, $enum);
    }

    public function isNotIn(array $enums, bool $strict = false): bool
    {
        return BackedEnumComparisons::isNotIn(static::class, $this, $enums, $strict);
    }

    public function compareTo(self | string | int | null $other): ?int
    {
        return BackedEnumComparisons::compareTo(static::class, $this, $other);
    }

    public function isAfter(self | string | int | null $other): bool
    {
        return BackedEnumComparisons::isAfter(static::class, $this, $other);
    }

    public function isAfterOrEqual(self | string | int | null $other): bool
    {
        return BackedEnumComparisons::isAfterOrEqual(static::class, $this, $other);
    }

    public function isBefore(self | string | int | null $other): bool
    {
        return BackedEnumComparisons::isBefore(static::class, $this, $other);
    }

    public function isBeforeOrEqual(self | string | int | null $other): bool
    {
        return BackedEnumComparisons::isBeforeOrEqual(static::class, $this, $other);
    }

    public function isBetween(
        self | string | int | null $start,
        self | string | int | null $end,
        bool $includeStart = true,
        bool $includeEnd = true,
    ): bool {
        return BackedEnumComparisons::isBetween(
            static::class,
            $this,
            $start,
            $end,
            $includeStart,
            $includeEnd,
        );
    }

    public function isFirst(): bool
    {
        return BackedEnumComparisons::isFirst(static::class, $this);
    }

    public function isLast(): bool
    {
        return BackedEnumComparisons::isLast(static::class, $this);
    }

    public function next(bool $wrap = false): ?static
    {
        /** @var static|null */
        return BackedEnumComparisons::next(static::class, $this, $wrap);
    }

    public function previous(bool $wrap = false): ?static
    {
        /** @var static|null */
        return BackedEnumComparisons::previous(static::class, $this, $wrap);
    }

    public function diff(self | string | int | null $other): ?int
    {
        return BackedEnumComparisons::diff(static::class, $this, $other);
    }

    public static function min(self | string | int | null ...$enums): ?static
    {
        /** @var static|null */
        return BackedEnumComparisons::min(static::class, $enums);
    }

    public static function max(self | string | int | null ...$enums): ?static
    {
        /** @var static|null */
        return BackedEnumComparisons::max(static::class, $enums);
    }
}
