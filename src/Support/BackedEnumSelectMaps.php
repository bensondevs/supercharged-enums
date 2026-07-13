<?php

declare(strict_types=1);

namespace BensonDevs\SuperchargedEnums\Support;

use BackedEnum;

final class BackedEnumSelectMaps
{
    /**
     * @param  class-string<BackedEnum>  $enumClass
     * @return array<string, string>
     */
    public static function options(string $enumClass): array
    {
        $options = [];
        foreach (self::filteredCases($enumClass) as $case) {
            $options[$case->value] = match (true) {
                method_exists($case, 'label') => $case->label(),
                method_exists($case, 'getLabel') => $case->getLabel(),
                method_exists($case, 'getName') => $case->getName(),
                default => $case->name,
            };
        }

        return $options;
    }

    /**
     * @param  class-string<BackedEnum>  $enumClass
     * @return array<string, string>
     */
    public static function asSelectDescriptions(string $enumClass): array
    {
        $descriptions = [];
        foreach (self::filteredCases($enumClass) as $enum) {
            $descriptions[$enum->value] = match (true) {
                method_exists($enum, 'getDescription') => $enum->getDescription(),
                method_exists($enum, 'getLabel') => $enum->getLabel(),
                default => $enum->name,
            };
        }

        return $descriptions;
    }

    /**
     * @param  class-string<BackedEnum>  $enumClass
     * @return array<int, BackedEnum>
     */
    public static function filteredCases(string $enumClass): array
    {
        if (method_exists($enumClass, 'selectables')) {
            /** @var array<int, BackedEnum|int|string> $allowed */
            $allowed = $enumClass::selectables();

            return array_values(array_filter(
                $enumClass::cases(),
                static fn ($case) => self::caseIsListed($enumClass, $case, $allowed),
            ));
        }

        if (method_exists($enumClass, 'unselectables')) {
            /** @var array<int, BackedEnum|int|string> $denied */
            $denied = $enumClass::unselectables();

            return array_values(array_filter(
                $enumClass::cases(),
                static fn ($case) => ! self::caseIsListed($enumClass, $case, $denied),
            ));
        }

        return $enumClass::cases();
    }

    /**
     * @param  class-string<BackedEnum>  $enumClass
     * @return array<int, BackedEnum>
     */
    public static function all(string $enumClass): array
    {
        return self::filteredCases($enumClass);
    }

    /**
     * @param  class-string<BackedEnum>  $enumClass
     * @return \Illuminate\Support\Collection<int, BackedEnum>
     */
    public static function collect(string $enumClass): object
    {
        if (! class_exists(\Illuminate\Support\Collection::class)) {
            throw new \RuntimeException(
                'collect() requires illuminate/support. Install it with: composer require illuminate/support',
            );
        }

        return new \Illuminate\Support\Collection(self::all($enumClass));
    }

    /**
     * @param  class-string<BackedEnum>  $enumClass
     * @param  array<int, BackedEnum|int|string>  $entries
     */
    private static function caseIsListed(string $enumClass, object $case, array $entries): bool
    {
        foreach ($entries as $entry) {
            if (self::entryMatchesCase($enumClass, $entry, $case)) {
                return true;
            }
        }

        return false;
    }

    private static function entryMatchesCase(string $enumClass, mixed $entry, object $case): bool
    {
        if ($entry instanceof $enumClass) {
            return $entry === $case;
        }

        if (is_string($entry) || is_int($entry)) {
            /** @var string|int $value */
            $value = $case->value;

            return $value === $entry;
        }

        return false;
    }
}
