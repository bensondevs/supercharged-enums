<?php

declare(strict_types=1);

namespace BensonDevs\SuperchargedEnums\Laravel\Console;

use BensonDevs\SuperchargedEnums\Laravel\Console\Support\EnumFromTableBuilder;
use BensonDevs\SuperchargedEnums\Laravel\Console\Support\EnumImporterNameResolver;
use BensonDevs\SuperchargedEnums\Laravel\Console\Support\EnumImportWriter;
use BensonDevs\SuperchargedEnums\Laravel\Console\Support\ImportProgress;
use BensonDevs\SuperchargedEnums\Laravel\Console\Support\TableColumnDetector;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use InvalidArgumentException;
use RuntimeException;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'supercharged-enums:import-from-table')]
final class ImportEnumFromTableCommand extends Command
{
    protected $signature = 'supercharged-enums:import-from-table
                            {table : The legacy lookup table to import}
                            {--class= : Enum class name (defaults from the table name)}
                            {--string : Force a string-backed enum}
                            {--int : Force an int-backed enum}
                            {--value-column= : Column used for enum backing values}
                            {--label-column= : Column used for getLabel()}
                            {--id-column= : ID column name}
                            {--sort-column= : Column used to order enum cases}
                            {--path=app/Enums : Output directory relative to the application base path}
                            {--namespace= : PHP namespace (defaults from the output path)}
                            {--aliases : Emit alias() mappings for legacy keys}
                            {--no-labels : Skip getLabel() even when a label column exists}
                            {--force : Overwrite an existing enum file}
                            {--connection= : Database connection name}';

    protected $description = 'Generate a backed enum with EnumExtension from a legacy lookup table';

    public function handle(
        TableColumnDetector $columnDetector,
        EnumFromTableBuilder $enumBuilder,
        EnumImportWriter $writer,
        EnumImporterNameResolver $nameResolver,
    ): int {
        $table = (string) $this->argument('table');
        $connection = $this->option('connection');

        try {
            $this->validateBackingFlags();

            $columns = $columnDetector->detect($table, is_string($connection) ? $connection : null, [
                'id_column' => $this->option('id-column'),
                'value_column' => $this->option('value-column'),
                'label_column' => $this->option('label-column'),
                'sort_column' => $this->option('sort-column'),
            ]);

            $query = DB::connection(is_string($connection) ? $connection : null)->table($table);
            $rowCount = $query->count();

            $progress = ImportProgress::fromOutput($this->output, (bool) $this->option('quiet'));
            $progress?->start($rowCount, 'Importing rows...');

            $rows = $query->get()->all();

            foreach ($rows as $_) {
                $progress?->advance();
            }

            $progress?->finish();

            $built = $enumBuilder->build($rows, $columns, [
                'backing_type' => $this->resolveBackingTypeOption(),
                'with_labels' => ! $this->option('no-labels'),
                'with_aliases' => (bool) $this->option('aliases'),
            ]);

            $uniqueCount = count($built['cases']);
            $progress?->line("Found {$uniqueCount} unique cases from {$rowCount} rows.");

            $class = $this->resolveClassName($table);
            $path = $this->resolveOutputPath($class);
            $namespace = $this->resolveNamespace($nameResolver);

            $writeResult = $writer->write(
                $path,
                $namespace,
                $class,
                $built['backing_type'],
                $built['cases'],
                [
                    'force' => (bool) $this->option('force'),
                    'interactive' => false,
                ],
            );

            $this->components->info("Enum [{$namespace}\\{$class}] created successfully.");
            $this->components->twoColumnDetail('Table', $table);
            $this->components->twoColumnDetail('Backing type', $built['backing_type']);
            $this->components->twoColumnDetail('Value column', $columns['value']);
            $this->components->twoColumnDetail('Label column', $columns['label'] ?? 'none');
            $this->components->twoColumnDetail('Sort column', $columns['sort']);
            $this->components->twoColumnDetail('Rows processed', (string) $rowCount);
            $this->components->twoColumnDetail('Unique cases', (string) $uniqueCount);
            $this->components->twoColumnDetail('Cases', (string) $uniqueCount);
            $this->components->twoColumnDetail('Path', $writeResult['path']);

            return self::SUCCESS;
        } catch (InvalidArgumentException | RuntimeException $exception) {
            $this->components->error($exception->getMessage());

            return self::FAILURE;
        }
    }

    private function validateBackingFlags(): void
    {
        if ($this->option('string') && $this->option('int')) {
            throw new InvalidArgumentException('Use only one of --string or --int.');
        }
    }

    private function resolveBackingTypeOption(): ?string
    {
        if ($this->option('string')) {
            return 'string';
        }

        if ($this->option('int')) {
            return 'int';
        }

        return null;
    }

    private function resolveClassName(string $table): string
    {
        $class = $this->option('class');

        if (is_string($class) && $class !== '') {
            return Str::studly($class);
        }

        return Str::studly(Str::singular($table));
    }

    private function resolveOutputPath(string $class): string
    {
        $relativePath = str_replace('\\', '/', (string) $this->option('path'));
        $relativePath = trim($relativePath, '/');

        return base_path($relativePath . '/' . $class . '.php');
    }

    private function resolveNamespace(EnumImporterNameResolver $nameResolver): string
    {
        $namespace = $this->option('namespace');

        if (is_string($namespace) && $namespace !== '') {
            return trim($namespace, '\\');
        }

        return $nameResolver->namespaceFromPath((string) $this->option('path'));
    }
}
