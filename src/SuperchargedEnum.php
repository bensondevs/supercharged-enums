<?php

declare(strict_types=1);

namespace BensonDevs\SuperchargedEnums;

use BackedEnum;
use BensonDevs\SuperchargedEnums\Support\BackedEnumComparisons;
use BensonDevs\SuperchargedEnums\Support\BackedEnumNaming;
use Stringable;
use UnitEnum;

/**
 * Runtime wrapper that exposes instance-level EnumExtension helpers for any backed enum.
 *
 * @template T of BackedEnum
 */
final class SuperchargedEnum implements Stringable
{
    /** @var T */
    private readonly BackedEnum $enum;

    /**
     * @param  T  $enum
     */
    public function __construct(BackedEnum $enum)
    {
        $this->enum = $enum;
    }

    /**
     * @return T
     */
    public function unwrap(): BackedEnum
    {
        return $this->enum;
    }

    public function is(UnitEnum | string | int | null $other): bool
    {
        if (method_exists($this->enum, 'is')) {
            return $this->enum->is($other);
        }

        return BackedEnumComparisons::is($this->enumClass(), $this->enum, $other);
    }

    public function isIn(array $enums, bool $strict = false): bool
    {
        if (method_exists($this->enum, 'isIn')) {
            return $this->enum->isIn($enums, $strict);
        }

        return BackedEnumComparisons::isIn($this->enumClass(), $this->enum, $enums, $strict);
    }

    public function isNot(UnitEnum | string | int $other): bool
    {
        if (method_exists($this->enum, 'isNot')) {
            return $this->enum->isNot($other);
        }

        return BackedEnumComparisons::isNot($this->enumClass(), $this->enum, $other);
    }

    public function isNotIn(array $enums, bool $strict = false): bool
    {
        if (method_exists($this->enum, 'isNotIn')) {
            return $this->enum->isNotIn($enums, $strict);
        }

        return BackedEnumComparisons::isNotIn($this->enumClass(), $this->enum, $enums, $strict);
    }

    public function compareTo(UnitEnum | string | int | null $other): ?int
    {
        if (method_exists($this->enum, 'compareTo')) {
            return $this->enum->compareTo($other);
        }

        return BackedEnumComparisons::compareTo($this->enumClass(), $this->enum, $other);
    }

    public function isAfter(UnitEnum | string | int | null $other): bool
    {
        if (method_exists($this->enum, 'isAfter')) {
            return $this->enum->isAfter($other);
        }

        return BackedEnumComparisons::isAfter($this->enumClass(), $this->enum, $other);
    }

    public function isAfterOrEqual(UnitEnum | string | int | null $other): bool
    {
        if (method_exists($this->enum, 'isAfterOrEqual')) {
            return $this->enum->isAfterOrEqual($other);
        }

        return BackedEnumComparisons::isAfterOrEqual($this->enumClass(), $this->enum, $other);
    }

    public function isBefore(UnitEnum | string | int | null $other): bool
    {
        if (method_exists($this->enum, 'isBefore')) {
            return $this->enum->isBefore($other);
        }

        return BackedEnumComparisons::isBefore($this->enumClass(), $this->enum, $other);
    }

    public function isBeforeOrEqual(UnitEnum | string | int | null $other): bool
    {
        if (method_exists($this->enum, 'isBeforeOrEqual')) {
            return $this->enum->isBeforeOrEqual($other);
        }

        return BackedEnumComparisons::isBeforeOrEqual($this->enumClass(), $this->enum, $other);
    }

    public function isBetween(
        UnitEnum | string | int | null $start,
        UnitEnum | string | int | null $end,
        bool $includeStart = true,
        bool $includeEnd = true,
    ): bool {
        if (method_exists($this->enum, 'isBetween')) {
            return $this->enum->isBetween($start, $end, $includeStart, $includeEnd);
        }

        return BackedEnumComparisons::isBetween(
            $this->enumClass(),
            $this->enum,
            $start,
            $end,
            $includeStart,
            $includeEnd,
        );
    }

    public function isFirst(): bool
    {
        if (method_exists($this->enum, 'isFirst')) {
            return $this->enum->isFirst();
        }

        return BackedEnumComparisons::isFirst($this->enumClass(), $this->enum);
    }

    public function isLast(): bool
    {
        if (method_exists($this->enum, 'isLast')) {
            return $this->enum->isLast();
        }

        return BackedEnumComparisons::isLast($this->enumClass(), $this->enum);
    }

    public function next(bool $wrap = false): ?BackedEnum
    {
        if (method_exists($this->enum, 'next')) {
            return $this->enum->next($wrap);
        }

        return BackedEnumComparisons::next($this->enumClass(), $this->enum, $wrap);
    }

    public function previous(bool $wrap = false): ?BackedEnum
    {
        if (method_exists($this->enum, 'previous')) {
            return $this->enum->previous($wrap);
        }

        return BackedEnumComparisons::previous($this->enumClass(), $this->enum, $wrap);
    }

    public function diff(UnitEnum | string | int | null $other): ?int
    {
        if (method_exists($this->enum, 'diff')) {
            return $this->enum->diff($other);
        }

        return BackedEnumComparisons::diff($this->enumClass(), $this->enum, $other);
    }

    public function getKey(): string | int
    {
        if (method_exists($this->enum, 'getKey')) {
            return $this->enum->getKey();
        }

        return BackedEnumNaming::getKey($this->enum);
    }

    public function getName(): string
    {
        if (method_exists($this->enum, 'getName')) {
            return $this->enum->getName();
        }

        return BackedEnumNaming::getName($this->enum);
    }

    public function __get(string $name): mixed
    {
        if ($name === 'name' || $name === 'value') {
            return $this->enum->{$name};
        }

        throw new \InvalidArgumentException(sprintf('Undefined property %s on %s.', $name, self::class));
    }

    public function __call(string $name, array $arguments): mixed
    {
        if (! method_exists($this->enum, $name)) {
            throw new \BadMethodCallException(sprintf(
                'Method %s::%s does not exist.',
                $this->enumClass(),
                $name,
            ));
        }

        return $this->enum->{$name}(...$arguments);
    }

    public function __toString(): string
    {
        if (is_string($this->enum->value)) {
            return $this->enum->value;
        }

        return (string) $this->enum->value;
    }

    /**
     * @return class-string<T>
     */
    private function enumClass(): string
    {
        return $this->enum::class;
    }
}
