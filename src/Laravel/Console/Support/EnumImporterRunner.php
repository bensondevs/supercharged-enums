<?php

declare(strict_types=1);

namespace BensonDevs\SuperchargedEnums\Laravel\Console\Support;

use BensonDevs\SuperchargedEnums\Laravel\Console\Contracts\EnumImporter;
use Closure;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use InvalidArgumentException;

final class EnumImporterRunner
{
    public function __construct(
        private readonly EnumFromTableBuilder $enumBuilder,
        private readonly EnumImporterNameResolver $nameResolver,
    ) {}

    /**
     * @return array{
     *     class: string,
     *     backing_type: string,
     *     cases: list<BuiltEnumCase>,
     *     case_count: int,
     *     row_count: int,
     *     unique_count: int,
     *     sources: list<string>,
     * }
     */
    public function run(
        EnumImporter $importer,
        ?string $duplicateStrategy = null,
        ?ImportProgress $progress = null,
    ): array {
        $connection = $importer->connection();
        $duplicateStrategy ??= $importer->onDuplicate();

        if (! in_array($duplicateStrategy, ['fail', 'last-wins'], true)) {
            throw new InvalidArgumentException("Duplicate strategy [{$duplicateStrategy}] is invalid. Use fail or last-wins.");
        }

        $totalRows = $this->countRows($importer, $connection);
        $progress?->start($totalRows, 'Importing rows...');

        $collection = $this->collectNormalizedRows($importer, $connection, $duplicateStrategy, $progress);
        $progress?->finish();
        $progress?->line("Found {$collection['unique_count']} unique cases from {$collection['row_count']} rows.");

        $built = $this->enumBuilder->buildFromNormalizedRows($collection['rows'], [
            'with_labels' => true,
            'with_aliases' => $importer->aliases(),
        ]);

        $importerClass = $importer::class;
        $enumClass = $importer->as() ?? $this->nameResolver->resolveEnumClassName($importerClass);

        return [
            'class' => $enumClass,
            'backing_type' => $built['backing_type'],
            'cases' => $built['cases'],
            'case_count' => count($built['cases']),
            'row_count' => $collection['row_count'],
            'unique_count' => $collection['unique_count'],
            'sources' => array_keys($importer->sources()),
        ];
    }

    private function countRows(EnumImporter $importer, ?string $connection): int
    {
        $total = 0;

        foreach ($importer->sources() as $table => $modifier) {
            if (is_int($table)) {
                $table = $modifier;
                $modifier = null;
            }

            if (! is_string($table) || $table === '') {
                continue;
            }

            $total += $this->buildQuery($table, $modifier, $connection)->count();
        }

        return $total;
    }

    /**
     * @return array{rows: list<object>, row_count: int, unique_count: int}
     */
    private function collectNormalizedRows(
        EnumImporter $importer,
        ?string $connection,
        string $duplicateStrategy,
        ?ImportProgress $progress,
    ): array {
        $merged = [];
        $seenBackingValues = [];
        $seenCaseNames = [];
        $rowCount = 0;

        foreach ($importer->sources() as $table => $modifier) {
            if (is_int($table)) {
                $table = $modifier;
                $modifier = null;
            }

            if (! is_string($table) || $table === '') {
                throw new InvalidArgumentException('Each importer source must be a table name string.');
            }

            if (Schema::connection($connection)->getColumnListing($table) === []) {
                throw new InvalidArgumentException("Table [{$table}] does not exist or has no columns.");
            }

            $rows = $this->fetchRows($table, $modifier, $connection);

            foreach ($rows as $row) {
                $rowCount++;
                $progress?->advance();

                $resolved = $importer->resolveRowFor($table, (array) $row);
                $normalized = $this->assertResolvedRow($resolved, $table);
                $backingKey = (string) $normalized->value;
                $caseNameKey = $this->previewCaseName((string) $normalized->name, $backingKey);

                if ($duplicateStrategy === 'fail') {
                    if (isset($seenBackingValues[$backingKey])) {
                        throw new InvalidArgumentException("Duplicate backing value [{$backingKey}] detected across sources.");
                    }

                    if (isset($seenCaseNames[$caseNameKey])) {
                        throw new InvalidArgumentException("Duplicate case name [{$caseNameKey}] detected across sources.");
                    }
                }

                if ($duplicateStrategy === 'last-wins') {
                    if (isset($seenBackingValues[$backingKey])) {
                        unset($merged[$seenBackingValues[$backingKey]]);
                    }

                    if (isset($seenCaseNames[$caseNameKey])) {
                        unset($merged[$seenCaseNames[$caseNameKey]]);
                    }
                }

                $index = count($merged);
                $merged[$index] = $normalized;
                $seenBackingValues[$backingKey] = $index;
                $seenCaseNames[$caseNameKey] = $index;
            }
        }

        if ($merged === []) {
            throw new InvalidArgumentException('No rows were found to import from the configured sources.');
        }

        return [
            'rows' => array_values($merged),
            'row_count' => $rowCount,
            'unique_count' => count($merged),
        ];
    }

    /**
     * @param  array<string, mixed>  $resolved
     */
    private function assertResolvedRow(array $resolved, string $table): object
    {
        foreach (['id', 'value', 'name', 'label', 'sort'] as $key) {
            if (! array_key_exists($key, $resolved)) {
                throw new InvalidArgumentException("Resolver for table [{$table}] is missing required key [{$key}].");
            }
        }

        $normalized = (object) [
            'id' => $resolved['id'],
            'value' => $resolved['value'],
            'name' => $resolved['name'],
            'label' => $resolved['label'],
            'sort' => $resolved['sort'],
        ];

        foreach (['value', 'name'] as $required) {
            if ($normalized->{$required} === null || $normalized->{$required} === '') {
                throw new InvalidArgumentException("Resolved row for table [{$table}] is missing required value for [{$required}].");
            }
        }

        return $normalized;
    }

    private function buildQuery(string $table, mixed $modifier, ?string $connection): Builder
    {
        $query = DB::connection($connection)->table($table);

        if ($modifier === null) {
            return $query;
        }

        if (! $modifier instanceof Closure) {
            throw new InvalidArgumentException("Source modifier for table [{$table}] must be a closure.");
        }

        $query = $modifier($query);

        if (! $query instanceof Builder) {
            throw new InvalidArgumentException("Source modifier for table [{$table}] must return a query builder.");
        }

        return $query;
    }

    /**
     * @return list<object>
     */
    private function fetchRows(string $table, mixed $modifier, ?string $connection): array
    {
        return $this->buildQuery($table, $modifier, $connection)->get()->all();
    }

    private function previewCaseName(string $name, string $backingValue): string
    {
        $source = trim($name) !== '' ? $name : $backingValue;
        $studly = str_replace(['-', ' '], '_', $source);
        $studly = \Illuminate\Support\Str::studly($studly);

        if ($studly === '' || preg_match('/^[0-9]/', $studly)) {
            $studly = 'Case' . $studly;
        }

        return $studly;
    }
}
