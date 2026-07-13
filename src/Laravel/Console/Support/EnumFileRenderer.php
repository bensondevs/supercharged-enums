<?php

declare(strict_types=1);

namespace BensonDevs\SuperchargedEnums\Laravel\Console\Support;

final class EnumFileRenderer
{
    public function __construct(
        private readonly string $stubPath,
    ) {}

    /**
     * @param  list<BuiltEnumCase>  $cases
     */
    public function render(string $namespace, string $class, string $backingType, array $cases): string
    {
        $stub = file_get_contents($this->stubPath);

        if ($stub === false) {
            throw new \RuntimeException("Unable to read enum stub at [{$this->stubPath}].");
        }

        $replacements = [
            '{{ namespace }}' => $namespace,
            '{{ class }}' => $class,
            '{{ type }}' => $backingType,
            '{{ cases }}' => $this->renderCases($cases, $backingType),
            '{{ label_method }}' => $this->renderLabelMethod($cases),
            '{{ alias_method }}' => $this->renderAliasMethod($cases),
        ];

        return str_replace(array_keys($replacements), array_values($replacements), $stub);
    }

    /**
     * @param  list<BuiltEnumCase>  $cases
     */
    private function renderCases(array $cases, string $backingType): string
    {
        $lines = [];

        foreach ($cases as $case) {
            $value = $backingType === 'int'
                ? (string) $case->backingValue
                : "'" . $this->escapeSingleQuotedString((string) $case->backingValue) . "'";

            $lines[] = "    case {$case->name} = {$value};";
        }

        return implode(PHP_EOL . PHP_EOL, $lines);
    }

    /**
     * @param  list<BuiltEnumCase>  $cases
     */
    private function renderLabelMethod(array $cases): string
    {
        $labeledCases = array_values(array_filter(
            $cases,
            static fn (BuiltEnumCase $case): bool => $case->label !== null,
        ));

        if ($labeledCases === []) {
            return '';
        }

        $arms = [];

        foreach ($labeledCases as $case) {
            $arms[] = "            self::{$case->name} => '" . $this->escapeSingleQuotedString($case->label) . "',";
        }

        return PHP_EOL . PHP_EOL . '    public function getLabel(): string' . PHP_EOL
            . '    {' . PHP_EOL
            . '        return match ($this) {' . PHP_EOL
            . implode(PHP_EOL, $arms) . PHP_EOL
            . '        };' . PHP_EOL
            . '    }';
    }

    /**
     * @param  list<BuiltEnumCase>  $cases
     */
    private function renderAliasMethod(array $cases): string
    {
        $aliasedCases = array_values(array_filter(
            $cases,
            static fn (BuiltEnumCase $case): bool => $case->aliases !== [],
        ));

        if ($aliasedCases === []) {
            return '';
        }

        $arms = [];

        foreach ($aliasedCases as $case) {
            $formattedAliases = array_map(
                fn (int | string $alias): string => is_int($alias)
                    ? (string) $alias
                    : "'" . $this->escapeSingleQuotedString($alias) . "'",
                $case->aliases,
            );

            $arms[] = '            self::' . $case->name . ' => [' . implode(', ', $formattedAliases) . '],';
        }

        return PHP_EOL . PHP_EOL . '    /**' . PHP_EOL
            . '     * @return list<string|int>' . PHP_EOL
            . '     */' . PHP_EOL
            . '    public function alias(): array' . PHP_EOL
            . '    {' . PHP_EOL
            . '        return match ($this) {' . PHP_EOL
            . implode(PHP_EOL, $arms) . PHP_EOL
            . '            default => [],' . PHP_EOL
            . '        };' . PHP_EOL
            . '    }';
    }

    private function escapeSingleQuotedString(string $value): string
    {
        return str_replace(['\\', "'"], ['\\\\', "\\'"], $value);
    }
}
