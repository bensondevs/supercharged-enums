<?php

declare(strict_types=1);

namespace BensonDevs\SuperchargedEnums\Laravel\Console;

use BensonDevs\SuperchargedEnums\Laravel\Console\Support\EnumImporterNameResolver;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use RuntimeException;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'make:enum-importer')]
final class MakeEnumImporterCommand extends Command
{
    protected $signature = 'make:enum-importer
                            {name : The importer class name}
                            {--path=app/EnumImporters : Output directory relative to the application base path}
                            {--force : Overwrite an existing importer file}';

    protected $description = 'Create a new enum importer class';

    public function handle(EnumImporterNameResolver $nameResolver): int
    {
        $name = Str::studly((string) $this->argument('name'));
        $relativePath = trim(str_replace('\\', '/', (string) $this->option('path')), '/');
        $namespace = $nameResolver->namespaceFromPath($relativePath);
        $path = base_path($relativePath . '/' . $name . '.php');

        if (File::exists($path) && ! $this->option('force')) {
            $this->components->error("Importer already exists at [{$path}].");

            return self::FAILURE;
        }

        $stubPath = __DIR__ . '/../../../stubs/enum-importer.stub';
        $stub = file_get_contents($stubPath);

        if ($stub === false) {
            throw new RuntimeException("Unable to read importer stub at [{$stubPath}].");
        }

        $contents = str_replace(
            ['{{ namespace }}', '{{ class }}'],
            [$namespace, $name],
            $stub,
        );

        File::ensureDirectoryExists(dirname($path));
        File::put($path, $contents);

        $this->components->info("Importer [{$namespace}\\{$name}] created successfully.");
        $this->components->twoColumnDetail('Path', $path);

        return self::SUCCESS;
    }
}
