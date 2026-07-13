<?php

declare(strict_types=1);

namespace BensonDevs\SuperchargedEnums;

use BensonDevs\SuperchargedEnums\Laravel\Console\ImportEnumFromTableCommand;
use BensonDevs\SuperchargedEnums\Laravel\Console\ImportEnumUsingCommand;
use BensonDevs\SuperchargedEnums\Laravel\Console\MakeEnumImporterCommand;
use BensonDevs\SuperchargedEnums\Laravel\Console\Support\EnumFileRenderer;
use BensonDevs\SuperchargedEnums\Laravel\Console\Support\EnumFromTableBuilder;
use BensonDevs\SuperchargedEnums\Laravel\Console\Support\EnumImporterNameResolver;
use BensonDevs\SuperchargedEnums\Laravel\Console\Support\EnumImporterRunner;
use BensonDevs\SuperchargedEnums\Laravel\Console\Support\EnumImportWriter;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class SuperchargedEnumsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(EnumFileRenderer::class, function (): EnumFileRenderer {
            return new EnumFileRenderer(__DIR__ . '/../stubs/enum.from-table.stub');
        });

        $this->app->singleton(EnumFromTableBuilder::class);
        $this->app->singleton(EnumImportWriter::class);
        $this->app->singleton(EnumImporterNameResolver::class);
        $this->app->singleton(EnumImporterRunner::class);
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../stubs/enum.backed.stub' => base_path('stubs/enum.backed.stub'),
        ], 'supercharged-enums-stubs');

        if ($this->app->runningInConsole() && class_exists(Schema::class)) {
            $this->commands([
                ImportEnumFromTableCommand::class,
                ImportEnumUsingCommand::class,
                MakeEnumImporterCommand::class,
            ]);
        }
    }
}
