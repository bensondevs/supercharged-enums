<?php

declare(strict_types=1);

namespace BensonDevs\SuperchargedEnums;

use BackedEnum;
use BensonDevs\SuperchargedEnums\Laravel\Casts\SuperchargedEnumCast;
use BensonDevs\SuperchargedEnums\Support\BackedEnumCaseListing;
use BensonDevs\SuperchargedEnums\Support\BackedEnumComparisons;
use BensonDevs\SuperchargedEnums\Support\BackedEnumCore;
use BensonDevs\SuperchargedEnums\Support\BackedEnumLookup;
use BensonDevs\SuperchargedEnums\Support\BackedEnumSelectMaps;
use UnitEnum;

/**
 * Runtime wrapper that exposes static EnumExtension helpers for any backed enum class.
 *
 * @template T of BackedEnum
 */
final class SuperchargedEnumType
{
    /**
     * @param  class-string<T>  $enumClass
     */
    public function __construct(private readonly string $enumClass)
    {
        if (! enum_exists($this->enumClass)) {
            throw new \InvalidArgumentException(sprintf('%s is not an enum.', $this->enumClass));
        }

        $reflection = new \ReflectionEnum($this->enumClass);

        if ($reflection->getBackingType() === null) {
            throw new \InvalidArgumentException(sprintf('%s must be a backed enum.', $this->enumClass));
        }
    }

    /**
     * @return class-string<T>
     */
    public function enumClass(): string
    {
        return $this->enumClass;
    }

    public function cast(bool $lenient = false): string
    {
        return SuperchargedEnumCast::of($this->enumClass, $lenient);
    }

    /**
     * @param  T|UnitEnum|string|int|null  $key
     * @return T|null
     */
    public function find(UnitEnum | string | int | null $key, bool $strict = false): ?BackedEnum
    {
        if (method_exists($this->enumClass, 'find')) {
            return $this->enumClass::find($key, $strict);
        }

        return BackedEnumLookup::find($this->enumClass, $key, $strict);
    }

    /**
     * @param  T|UnitEnum|string|int|null  $key
     * @return T
     */
    public function findOrDefault(UnitEnum | string | int | null $key, bool $strict = false): BackedEnum
    {
        if (method_exists($this->enumClass, 'findOrDefault')) {
            return $this->enumClass::findOrDefault($key, $strict);
        }

        return BackedEnumLookup::findOrDefault($this->enumClass, $key, $strict);
    }

    /**
     * @return T
     */
    public function default(): BackedEnum
    {
        if (method_exists($this->enumClass, 'default')) {
            return $this->enumClass::default();
        }

        return BackedEnumCore::default($this->enumClass);
    }

    /**
     * @return T
     */
    public function getDefault(): BackedEnum
    {
        if (method_exists($this->enumClass, 'getDefault')) {
            return $this->enumClass::getDefault();
        }

        return $this->default();
    }

    /**
     * @return T
     */
    public function random(): BackedEnum
    {
        if (method_exists($this->enumClass, 'random')) {
            return $this->enumClass::random();
        }

        return BackedEnumCore::random($this->enumClass);
    }

    /**
     * @return array<int, string>
     */
    public function names(): array
    {
        if (method_exists($this->enumClass, 'names')) {
            return $this->enumClass::names();
        }

        return BackedEnumCaseListing::names($this->enumClass);
    }

    /**
     * @return array<int, string|int>
     */
    public function values(): array
    {
        if (method_exists($this->enumClass, 'values')) {
            return $this->enumClass::values();
        }

        return BackedEnumCaseListing::values($this->enumClass);
    }

    /**
     * @return array<string, string>
     */
    public function options(): array
    {
        if (method_exists($this->enumClass, 'options')) {
            return $this->enumClass::options();
        }

        return BackedEnumSelectMaps::options($this->enumClass);
    }

    /**
     * @return array<string, string>
     */
    public function asSelectOptions(): array
    {
        return $this->options();
    }

    /**
     * @return array<string, string>
     */
    public function asSelectDescriptions(): array
    {
        if (method_exists($this->enumClass, 'asSelectDescriptions')) {
            return $this->enumClass::asSelectDescriptions();
        }

        return BackedEnumSelectMaps::asSelectDescriptions($this->enumClass);
    }

    /**
     * @return array<int, T>
     */
    public function filteredCases(): array
    {
        if (method_exists($this->enumClass, 'filteredCases')) {
            return $this->enumClass::filteredCases();
        }

        return BackedEnumSelectMaps::filteredCases($this->enumClass);
    }

    /**
     * @param  T|UnitEnum|string|int|null  ...$enums
     * @return T|null
     */
    public function min(UnitEnum | string | int | null ...$enums): ?BackedEnum
    {
        if (method_exists($this->enumClass, 'min')) {
            return $this->enumClass::min(...$enums);
        }

        return BackedEnumComparisons::min($this->enumClass, $enums);
    }

    /**
     * @param  T|UnitEnum|string|int|null  ...$enums
     * @return T|null
     */
    public function max(UnitEnum | string | int | null ...$enums): ?BackedEnum
    {
        if (method_exists($this->enumClass, 'max')) {
            return $this->enumClass::max(...$enums);
        }

        return BackedEnumComparisons::max($this->enumClass, $enums);
    }
}
