<?php

declare(strict_types=1);

namespace BensonDevs\SuperchargedEnums\Laravel\Console;

use BensonDevs\SuperchargedEnums\Laravel\Console\Contracts\EnumImporter;
use BensonDevs\SuperchargedEnums\Laravel\Console\Support\EnumImporterNameResolver;
use BensonDevs\SuperchargedEnums\Laravel\Console\Support\EnumImporterRunner;
use BensonDevs\SuperchargedEnums\Laravel\Console\Support\EnumImportWriter;
use BensonDevs\SuperchargedEnums\Laravel\Console\Support\ImportProgress;
use Illuminate\Console\Command;
use InvalidArgumentException;
use RuntimeException;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'supercharged-enums:import-enum-using')]
final class ImportEnumUsingCommand extends Command
{
    protected $signature = 'supercharged-enums:import-enum-using
                            {importer : Importer class name}
                            {--force : Overwrite without prompting}
                            {--path=app/Enums : Enum output directory relative to the application base path}
                            {--namespace= : PHP namespace (defaults from the output path)}
                            {--duplicates= : Override duplicate strategy (fail or last-wins)}';

    protected $description = 'Generate a backed enum using a configured enum importer class';

    public function handle(
        EnumImporterRunner $runner,
        EnumImportWriter $writer,
        EnumImporterNameResolver $nameResolver,
    ): int {
        try {
            $importer = $this->resolveImporter($nameResolver);
            $duplicateStrategy = $this->option('duplicates');
            $progress = ImportProgress::fromOutput($this->output, (bool) $this->option('quiet'));

            $result = $runner->run(
                $importer,
                is_string($duplicateStrategy) && $duplicateStrategy !== '' ? $duplicateStrategy : null,
                $progress,
            );

            $class = $result['class'];
            $namespace = $this->resolveNamespace($nameResolver);
            $path = $this->resolveOutputPath($class, (string) $this->option('path'));

            $writeResult = $writer->write(
                $path,
                $namespace,
                $class,
                $result['backing_type'],
                $result['cases'],
                [
                    'force' => (bool) $this->option('force'),
                    'interactive' => ! $this->option('no-interaction'),
                    'confirm' => fn (string $message): bool => $this->confirm($message),
                ],
            );

            if (! $writeResult['written']) {
                $this->components->warn('Import cancelled. Existing enum was left unchanged.');

                return self::FAILURE;
            }

            $this->components->info("Enum [{$namespace}\\{$class}] created successfully.");
            $this->components->twoColumnDetail('Importer', $importer::class);
            $this->components->twoColumnDetail('Backing type', $result['backing_type']);
            $this->components->twoColumnDetail('Sources', implode(', ', $result['sources']));
            $this->components->twoColumnDetail('Rows processed', (string) $result['row_count']);
            $this->components->twoColumnDetail('Unique cases', (string) $result['unique_count']);
            $this->components->twoColumnDetail('Cases', (string) $result['case_count']);
            $this->components->twoColumnDetail('Path', $writeResult['path']);

            return self::SUCCESS;
        } catch (InvalidArgumentException | RuntimeException $exception) {
            $this->components->error($exception->getMessage());

            return self::FAILURE;
        }
    }

    private function resolveImporter(EnumImporterNameResolver $nameResolver): EnumImporter
    {
        $input = (string) $this->argument('importer');

        if (str_contains($input, '\\')) {
            $class = ltrim($input, '\\');
        } else {
            $class = $nameResolver->resolveImporterClass($input, 'App\\EnumImporters');
        }

        if (! class_exists($class)) {
            throw new InvalidArgumentException("Importer class [{$class}] was not found.");
        }

        $importer = app($class);

        if (! $importer instanceof EnumImporter) {
            throw new InvalidArgumentException("Class [{$class}] must extend " . EnumImporter::class . '.');
        }

        return $importer;
    }

    private function resolveNamespace(EnumImporterNameResolver $nameResolver): string
    {
        $namespace = $this->option('namespace');

        if (is_string($namespace) && $namespace !== '') {
            return trim($namespace, '\\');
        }

        return $nameResolver->namespaceFromPath((string) $this->option('path'));
    }

    private function resolveOutputPath(string $class, string $relativePath): string
    {
        return base_path(trim(str_replace('\\', '/', $relativePath), '/') . '/' . $class . '.php');
    }
}
