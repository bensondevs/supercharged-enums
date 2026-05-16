<?php

declare(strict_types=1);

namespace BensonDevs\SuperchargedEnums\Concerns;

trait EnumSelectMaps
{
    /**
     * @return array<string, string>
     */
    public static function options(): array
    {
        $options = [];
        foreach (self::filteredCases() as $case) {
            $options[$case->value] = match (true) {
                method_exists($case, 'label') => $case->label(),
                method_exists($case, 'getLabel') => $case->getLabel(),
                method_exists($case, 'getName') => $case->getName(),
                default => $case->name,
            };
        }

        return $options;
    }

    public static function asSelectOptions(): array
    {
        return self::options();
    }

    /**
     * @return array<string, string>
     */
    public static function asSelectDescriptions(): array
    {
        $descriptions = [];
        foreach (self::filteredCases() as $enum) {
            $descriptions[$enum->value] = match (true) {
                method_exists($enum, 'getDescription') => $enum->getDescription(),
                method_exists($enum, 'getLabel') => $enum->getLabel(),
                default => $enum->name,
            };
        }

        return $descriptions;
    }

    /**
     * Cases included in select maps. When {@see selectables()} exists it wins over {@see unselectables()}.
     *
     * @return array<static>
     */
    public static function filteredCases(): array
    {
        if (method_exists(static::class, 'selectables')) {
            /** @var array<int, static|int|string> $allowed */
            $allowed = static::selectables();

            return array_values(array_filter(
                self::cases(),
                static fn ($case) => self::caseIsListed($case, $allowed),
            ));
        }

        if (method_exists(static::class, 'unselectables')) {
            /** @var array<int, static|int|string> $denied */
            $denied = static::unselectables();

            return array_values(array_filter(
                self::cases(),
                static fn ($case) => ! self::caseIsListed($case, $denied),
            ));
        }

        return self::cases();
    }

    /**
     * @param  array<int, static|int|string>  $entries
     */
    private static function caseIsListed(object $case, array $entries): bool
    {
        foreach ($entries as $entry) {
            if (self::entryMatchesCase($entry, $case)) {
                return true;
            }
        }

        return false;
    }

    private static function entryMatchesCase(mixed $entry, object $case): bool
    {
        if ($entry instanceof static) {
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
