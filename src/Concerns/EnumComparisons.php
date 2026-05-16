<?php

declare(strict_types=1);

namespace BensonDevs\SuperchargedEnums\Concerns;

/**
 * Equality and declaration-order comparisons.
 *
 * Ordering uses {@see static::cases()} iteration order, not backing values.
 * Requires {@see EnumLookup} for `find()` resolution of scalar operands.
 */
trait EnumComparisons
{
    public function is(self|string|int|null $enum): bool
    {
        $resolved = self::find($enum);

        return $resolved !== null && $this === $resolved;
    }

    public function isIn(array $enums, bool $strict = false): bool
    {
        $normalizedEnums = [];
        foreach ($enums as $enum) {
            $resolved = self::find($enum);
            if ($resolved !== null) {
                $normalizedEnums[] = $resolved;
            }
        }

        return in_array($this, $normalizedEnums, strict: $strict);
    }

    public function isNot(self|string|int $enum): bool
    {
        return ! $this->is($enum);
    }

    public function isNotIn(array $enums, bool $strict = false): bool
    {
        return ! $this->isIn($enums, $strict);
    }

    public function compareTo(self|string|int|null $other): ?int
    {
        $resolved = self::find($other);

        if ($resolved === null) {
            return null;
        }

        return $this->caseIndex() <=> $resolved->caseIndex();
    }

    public function isAfter(self|string|int|null $other): bool
    {
        return $this->compareTo($other) === 1;
    }

    public function isAfterOrEqual(self|string|int|null $other): bool
    {
        $comparison = $this->compareTo($other);

        return $comparison !== null && $comparison >= 0;
    }

    public function isBefore(self|string|int|null $other): bool
    {
        return $this->compareTo($other) === -1;
    }

    public function isBeforeOrEqual(self|string|int|null $other): bool
    {
        $comparison = $this->compareTo($other);

        return $comparison !== null && $comparison <= 0;
    }

    public function isBetween(
        self|string|int|null $start,
        self|string|int|null $end,
        bool $includeStart = true,
        bool $includeEnd = true,
    ): bool {
        $startResolved = self::find($start);
        $endResolved = self::find($end);

        if ($startResolved === null || $endResolved === null) {
            return false;
        }

        $index = $this->caseIndex();
        $startIndex = $startResolved->caseIndex();
        $endIndex = $endResolved->caseIndex();

        if ($startIndex > $endIndex) {
            [$startIndex, $endIndex] = [$endIndex, $startIndex];
            [$includeStart, $includeEnd] = [$includeEnd, $includeStart];
        }

        $afterStart = $includeStart ? $index >= $startIndex : $index > $startIndex;
        $beforeEnd = $includeEnd ? $index <= $endIndex : $index < $endIndex;

        return $afterStart && $beforeEnd;
    }

    public function isFirst(): bool
    {
        return $this->caseIndex() === 0;
    }

    public function isLast(): bool
    {
        return $this->caseIndex() === count(self::cases()) - 1;
    }

    public function next(bool $wrap = false): ?static
    {
        $cases = self::cases();
        $index = $this->caseIndex();

        if ($index < count($cases) - 1) {
            return $cases[$index + 1];
        }

        return $wrap ? $cases[0] : null;
    }

    public function previous(bool $wrap = false): ?static
    {
        $cases = self::cases();
        $index = $this->caseIndex();

        if ($index > 0) {
            return $cases[$index - 1];
        }

        return $wrap ? $cases[count($cases) - 1] : null;
    }

    public function diff(self|string|int|null $other): ?int
    {
        $resolved = self::find($other);

        if ($resolved === null) {
            return null;
        }

        return $this->caseIndex() - $resolved->caseIndex();
    }

    public static function min(self|string|int|null ...$enums): ?static
    {
        return self::extremeCase($enums, lowest: true);
    }

    public static function max(self|string|int|null ...$enums): ?static
    {
        return self::extremeCase($enums, lowest: false);
    }

    private function caseIndex(): int
    {
        $index = array_search($this, self::cases(), true);

        assert($index !== false);

        return $index;
    }

    /**
     * @param  array<int, self|string|int|null>  $enums
     */
    private static function extremeCase(array $enums, bool $lowest): ?static
    {
        $resolved = [];

        foreach ($enums as $enum) {
            $found = self::find($enum);

            if ($found !== null) {
                $resolved[] = $found;
            }
        }

        if ($resolved === []) {
            return null;
        }

        $extreme = $resolved[0];

        foreach (array_slice($resolved, 1) as $case) {
            if ($lowest) {
                if ($case->caseIndex() < $extreme->caseIndex()) {
                    $extreme = $case;
                }
            } elseif ($case->caseIndex() > $extreme->caseIndex()) {
                $extreme = $case;
            }
        }

        return $extreme;
    }
}
