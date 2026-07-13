<?php

declare(strict_types=1);

namespace BensonDevs\SuperchargedEnums\Laravel\Console\Support;

use Illuminate\Support\Str;

final class EnumFromTableBuilder
{
    /**
     * @param  list<object>  $rows
     * @param  array{
     *     id: string,
     *     value: string,
     *     label: ?string,
     *     sort: string,
     *     value_is_integer: bool,
     *     available_columns: list<string>,
     * }  $columns
     * @param  array{
     *     backing_type?: ?string,
     *     with_labels?: bool,
     *     with_aliases?: bool,
     * }  $options
     * @return array{
     *     backing_type: string,
     *     cases: list<BuiltEnumCase>,
     * }
     */
    public function build(array $rows, array $columns, array $options = []): array
    {
        if ($rows === []) {
            throw new \InvalidArgumentException('The table has no rows to import.');
        }

        $backingType = $this->resolveBackingType($columns, $options['backing_type'] ?? null);
        $withLabels = ($options['with_labels'] ?? true) && $columns['label'] !== null;
        $withAliases = ($options['with_aliases'] ?? false) && $this->canGenerateAliases($columns, $backingType);

        $sortedRows = $this->sortRows($rows, $columns['sort']);
        $cases = [];
        $seenBackingValues = [];
        $seenCaseNames = [];

        foreach ($sortedRows as $row) {
            $rowArray = (array) $row;
            $backingValue = $this->resolveBackingValue($rowArray, $columns['value'], $backingType);
            $caseName = $this->resolveCaseName($rowArray, $columns, $backingValue);

            if (isset($seenBackingValues[(string) $backingValue])) {
                throw new \InvalidArgumentException("Duplicate backing value [{$backingValue}] detected.");
            }

            if (isset($seenCaseNames[$caseName])) {
                throw new \InvalidArgumentException("Duplicate case name [{$caseName}] detected.");
            }

            $seenBackingValues[(string) $backingValue] = true;
            $seenCaseNames[$caseName] = true;

            $label = $withLabels && $columns['label'] !== null
                ? (string) $rowArray[$columns['label']]
                : null;

            $aliases = $withAliases
                ? $this->resolveAliases($rowArray, $columns, $backingType, $backingValue)
                : [];

            $cases[] = new BuiltEnumCase($caseName, $backingValue, $label, $aliases);
        }

        return [
            'backing_type' => $backingType,
            'cases' => $cases,
        ];
    }

    /**
     * @param  list<object>  $rows
     * @return list<object>
     */
    private function sortRows(array $rows, string $sortColumn): array
    {
        usort($rows, static function (object $left, object $right) use ($sortColumn): int {
            $leftValue = ((array) $left)[$sortColumn] ?? null;
            $rightValue = ((array) $right)[$sortColumn] ?? null;

            return $leftValue <=> $rightValue;
        });

        return $rows;
    }

    /**
     * @param  array{
     *     id: string,
     *     value: string,
     *     label: ?string,
     *     sort: string,
     *     value_is_integer: bool,
     *     available_columns: list<string>,
     * }  $columns
     */
    private function resolveBackingType(array $columns, ?string $override): string
    {
        if ($override === 'string' || $override === 'int') {
            return $override;
        }

        if ($override !== null) {
            throw new \InvalidArgumentException("Backing type [{$override}] is invalid. Use string or int.");
        }

        return $columns['value_is_integer'] ? 'int' : 'string';
    }

    /**
     * @param  array<string, mixed>  $row
     */
    private function resolveBackingValue(array $row, string $valueColumn, string $backingType): string | int
    {
        if (! array_key_exists($valueColumn, $row)) {
            throw new \InvalidArgumentException("Row is missing value column [{$valueColumn}].");
        }

        $value = $row[$valueColumn];

        if ($backingType === 'int') {
            if (! is_numeric($value)) {
                throw new \InvalidArgumentException("Value [{$value}] in column [{$valueColumn}] is not numeric.");
            }

            return (int) $value;
        }

        return (string) $value;
    }

    /**
     * @param  array<string, mixed>  $row
     * @param  array{
     *     id: string,
     *     value: string,
     *     label: ?string,
     *     sort: string,
     *     value_is_integer: bool,
     *     available_columns: list<string>,
     * }  $columns
     */
    private function resolveCaseName(array $row, array $columns, string | int $backingValue): string
    {
        $source = $columns['value'] === $columns['id']
            ? $this->firstPresentString($row, ['name', 'slug', 'code', 'label', 'title'])
            : (string) $row[$columns['value']];

        if ($source === null || $source === '') {
            $source = (string) $backingValue;
        }

        return $this->toValidCaseName($source);
    }

    /**
     * @param  array<string, mixed>  $row
     * @param  list<string>  $candidates
     */
    private function firstPresentString(array $row, array $candidates): ?string
    {
        foreach ($candidates as $candidate) {
            if (! array_key_exists($candidate, $row)) {
                continue;
            }

            $value = trim((string) $row[$candidate]);

            if ($value !== '') {
                return $value;
            }
        }

        return null;
    }

    private function toValidCaseName(string $source): string
    {
        $name = Str::studly(str_replace(['-', ' '], '_', $source));

        if ($name === '' || preg_match('/^[0-9]/', $name)) {
            $name = 'Case' . $name;
        }

        if (! preg_match('/^[A-Za-z_][A-Za-z0-9_]*$/', $name)) {
            $name = 'Case' . preg_replace('/[^A-Za-z0-9_]/', '', $name);
        }

        return $name;
    }

    /**
     * @param  array{
     *     id: string,
     *     value: string,
     *     label: ?string,
     *     sort: string,
     *     value_is_integer: bool,
     *     available_columns: list<string>,
     * }  $columns
     */
    private function canGenerateAliases(array $columns, string $backingType): bool
    {
        if ($backingType === 'string' && $columns['value'] !== $columns['id']) {
            return true;
        }

        if ($backingType === 'int' && $columns['value'] === $columns['id']) {
            return $this->hasAlternateKeyColumn($columns);
        }

        return false;
    }

    /**
     * @param  array{
     *     id: string,
     *     value: string,
     *     label: ?string,
     *     sort: string,
     *     value_is_integer: bool,
     *     available_columns: list<string>,
     * }  $columns
     */
    private function hasAlternateKeyColumn(array $columns): bool
    {
        foreach (['name', 'slug', 'code'] as $candidate) {
            if (
                in_array($candidate, $columns['available_columns'], true)
                && $candidate !== $columns['value']
            ) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param  array<string, mixed>  $row
     * @param  array{
     *     id: string,
     *     value: string,
     *     label: ?string,
     *     sort: string,
     *     value_is_integer: bool,
     *     available_columns: list<string>,
     * }  $columns
     * @return array<int, int|string>
     */
    private function resolveAliases(
        array $row,
        array $columns,
        string $backingType,
        string | int $backingValue,
    ): array {
        $aliases = [];

        if ($backingType === 'string' && $columns['value'] !== $columns['id'] && isset($row[$columns['id']])) {
            $id = $row[$columns['id']];

            if (is_numeric($id) && (string) $id !== (string) $backingValue) {
                $aliases[] = (int) $id;
            }
        }

        if ($backingType === 'int' && $columns['value'] === $columns['id']) {
            $alternate = $this->firstPresentString($row, ['name', 'slug', 'code']);

            if ($alternate !== null && $alternate !== (string) $backingValue) {
                $aliases[] = $alternate;
            }
        }

        return array_values(array_unique($aliases, SORT_REGULAR));
    }
}
