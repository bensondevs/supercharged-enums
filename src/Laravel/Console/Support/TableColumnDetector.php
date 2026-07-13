<?php

declare(strict_types=1);

namespace BensonDevs\SuperchargedEnums\Laravel\Console\Support;

use Illuminate\Support\Facades\Schema;

final class TableColumnDetector
{
    private const ID_CANDIDATES = ['id'];

    private const VALUE_CANDIDATES = ['slug', 'code', 'name', 'id'];

    private const LABEL_CANDIDATES = ['label', 'title', 'description', 'name'];

    private const SORT_CANDIDATES = ['sort_order', 'position', 'order'];

    /**
     * @param  array{
     *     id_column?: ?string,
     *     value_column?: ?string,
     *     label_column?: ?string,
     *     sort_column?: ?string,
     * }  $overrides
     * @return array{
     *     id: string,
     *     value: string,
     *     label: ?string,
     *     sort: string,
     *     value_is_integer: bool,
     *     available_columns: list<string>,
     * }
     */
    public function detect(string $table, ?string $connection = null, array $overrides = []): array
    {
        $columns = Schema::connection($connection)->getColumnListing($table);

        if ($columns === []) {
            throw new \InvalidArgumentException("Table [{$table}] does not exist or has no columns.");
        }

        $idColumn = $this->resolveColumn($columns, $overrides['id_column'] ?? null, self::ID_CANDIDATES)
            ?? 'id';

        $valueColumn = $this->resolveColumn($columns, $overrides['value_column'] ?? null, self::VALUE_CANDIDATES)
            ?? $idColumn;

        $labelColumn = $this->resolveLabelColumn($columns, $overrides['label_column'] ?? null, $valueColumn);

        $sortColumn = $this->resolveColumn($columns, $overrides['sort_column'] ?? null, self::SORT_CANDIDATES)
            ?? $idColumn;

        $valueIsInteger = $this->columnIsInteger($table, $valueColumn, $connection)
            || $valueColumn === 'id';

        return [
            'id' => $idColumn,
            'value' => $valueColumn,
            'label' => $labelColumn,
            'sort' => $sortColumn,
            'value_is_integer' => $valueIsInteger,
            'available_columns' => $columns,
        ];
    }

    /**
     * @param  list<string>  $columns
     * @param  list<string>  $candidates
     */
    private function resolveColumn(array $columns, ?string $override, array $candidates): ?string
    {
        if ($override !== null) {
            $this->assertColumnExists($columns, $override);

            return $override;
        }

        foreach ($candidates as $candidate) {
            if (in_array($candidate, $columns, true)) {
                return $candidate;
            }
        }

        return null;
    }

    /**
     * @param  list<string>  $columns
     */
    private function resolveLabelColumn(array $columns, ?string $override, string $valueColumn): ?string
    {
        if ($override !== null) {
            $this->assertColumnExists($columns, $override);

            return $override;
        }

        foreach (self::LABEL_CANDIDATES as $candidate) {
            if ($candidate === $valueColumn) {
                continue;
            }

            if (in_array($candidate, $columns, true)) {
                return $candidate;
            }
        }

        return null;
    }

    /**
     * @param  list<string>  $columns
     */
    private function assertColumnExists(array $columns, string $column): void
    {
        if (! in_array($column, $columns, true)) {
            throw new \InvalidArgumentException("Column [{$column}] does not exist on the table.");
        }
    }

    private function columnIsInteger(string $table, string $column, ?string $connection): bool
    {
        $type = Schema::connection($connection)->getColumnType($table, $column);

        return in_array($type, ['integer', 'bigint', 'smallint', 'tinyint', 'mediumint'], true);
    }
}
