<?php

declare(strict_types=1);

namespace BensonDevs\SuperchargedEnums\Tests\Laravel\Console;

use BensonDevs\SuperchargedEnums\SuperchargedEnumsServiceProvider;
use BensonDevs\SuperchargedEnums\Tests\Fixtures\EnumImporters\AsOverrideEnumImporter;
use BensonDevs\SuperchargedEnums\Tests\Fixtures\EnumImporters\CustomResolverEnumImporter;
use BensonDevs\SuperchargedEnums\Tests\Fixtures\EnumImporters\DuplicateFailEnumImporter;
use BensonDevs\SuperchargedEnums\Tests\Fixtures\EnumImporters\FilteredQueryEnumImporter;
use BensonDevs\SuperchargedEnums\Tests\Fixtures\EnumImporters\LastWinsDuplicateEnumImporter;
use BensonDevs\SuperchargedEnums\Tests\Fixtures\EnumImporters\MultiTableEnumImporter;
use BensonDevs\SuperchargedEnums\Tests\Fixtures\EnumImporters\SingleTableEnumImporter;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Orchestra\Testbench\TestCase;

class EnumImporterTest extends TestCase
{
    private string $tempBasePath;

    protected function setUp(): void
    {
        $this->tempBasePath = sys_get_temp_dir() . '/supercharged-enums-importer-' . uniqid('', true);

        parent::setUp();

        File::ensureDirectoryExists($this->tempBasePath . '/app/Enums');
        File::ensureDirectoryExists($this->tempBasePath . '/app/EnumImporters');
        $this->app->setBasePath($this->tempBasePath);

        $this->createOccupancyTypesTable();
        $this->createLegacyOccupanciesTable();
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('occupancy_types');
        Schema::dropIfExists('legacy_occupancies');

        File::deleteDirectory($this->tempBasePath);

        parent::tearDown();
    }

    protected function getPackageProviders($app): array
    {
        return [
            SuperchargedEnumsServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        tap($app['config'], function ($config): void {
            $config->set('database.default', 'testing');
            $config->set('database.connections.testing', [
                'driver' => 'sqlite',
                'database' => ':memory:',
                'prefix' => '',
            ]);
        });
    }

    public function test_make_enum_importer_creates_importer_class(): void
    {
        $this->artisan('make:enum-importer', ['name' => 'OccupancyEnumImporter'])
            ->assertSuccessful();

        $path = $this->tempBasePath . '/app/EnumImporters/OccupancyEnumImporter.php';
        expect(file_exists($path))->toBeTrue();

        $contents = file_get_contents($path);
        expect($contents)->toContain('class OccupancyEnumImporter extends EnumImporter')
            ->toContain('public function sources(): array');

        expect($this->phpSyntaxIsValid($path))->toBeTrue();
    }

    public function test_imports_enum_from_single_table_importer(): void
    {
        DB::table('occupancy_types')->insert([
            ['id' => 1, 'code' => 'single', 'name' => 'single', 'label' => 'Single Unit'],
            ['id' => 2, 'code' => 'double', 'name' => 'double', 'label' => 'Double Unit'],
        ]);

        $this->artisan('supercharged-enums:import-enum-using', [
            'importer' => SingleTableEnumImporter::class,
        ])
            ->expectsOutputToContain('Found 2 unique cases from 2 rows.')
            ->expectsOutputToContain('Unique cases')
            ->assertSuccessful();

        $path = $this->tempBasePath . '/app/Enums/SingleTableEnum.php';
        $contents = file_get_contents($path);

        expect($contents)->toContain('enum SingleTableEnum: int')
            ->toContain('case Single = 1;')
            ->toContain("self::Single => 'Single Unit'");
    }

    public function test_imports_enum_from_multiple_tables(): void
    {
        DB::table('occupancy_types')->insert([
            ['id' => 1, 'code' => 'single', 'name' => 'single', 'label' => 'Single Unit'],
        ]);
        DB::table('legacy_occupancies')->insert([
            ['id' => 2, 'name' => 'legacy', 'label' => 'Legacy Unit'],
        ]);

        $this->artisan('supercharged-enums:import-enum-using', [
            'importer' => MultiTableEnumImporter::class,
        ])->assertSuccessful();

        $contents = file_get_contents($this->tempBasePath . '/app/Enums/MultiTableEnum.php');

        expect($contents)->toContain('case Single = 1;')
            ->toContain('case Legacy = 2;');
    }

    public function test_supports_custom_resolve_using_mapping(): void
    {
        DB::table('occupancy_types')->insert([
            ['id' => 1, 'code' => 'studio', 'name' => 'Studio Name', 'label' => 'Should Not Be Used'],
        ]);

        $this->artisan('supercharged-enums:import-enum-using', [
            'importer' => CustomResolverEnumImporter::class,
        ])->assertSuccessful();

        $contents = file_get_contents($this->tempBasePath . '/app/Enums/CustomResolverEnum.php');

        expect($contents)->toContain("case Studio = 'studio';")
            ->toContain("self::Studio => 'Studio Name'");
    }

    public function test_supports_query_closure_on_source(): void
    {
        DB::table('occupancy_types')->insert([
            ['id' => 1, 'code' => 'active', 'name' => 'active', 'label' => 'Active'],
            ['id' => 2, 'code' => 'inactive', 'name' => 'inactive', 'label' => 'Inactive'],
        ]);

        $this->artisan('supercharged-enums:import-enum-using', [
            'importer' => FilteredQueryEnumImporter::class,
        ])->assertSuccessful();

        $contents = file_get_contents($this->tempBasePath . '/app/Enums/FilteredQueryEnum.php');

        expect($contents)->toContain('case Active = 1;')
            ->not->toContain('Inactive');
    }

    public function test_as_method_overrides_enum_class_name(): void
    {
        DB::table('occupancy_types')->insert([
            ['id' => 1, 'code' => 'single', 'name' => 'single', 'label' => 'Single Unit'],
        ]);

        $this->artisan('supercharged-enums:import-enum-using', [
            'importer' => AsOverrideEnumImporter::class,
        ])->assertSuccessful();

        expect(file_exists($this->tempBasePath . '/app/Enums/PowerfulEnum.php'))->toBeTrue();
    }

    public function test_duplicate_backing_values_fail_by_default(): void
    {
        DB::table('occupancy_types')->insert([
            ['id' => 1, 'code' => 'shared', 'name' => 'first', 'label' => 'First'],
        ]);
        DB::table('legacy_occupancies')->insert([
            ['id' => 1, 'name' => 'second', 'label' => 'Second'],
        ]);

        $this->artisan('supercharged-enums:import-enum-using', [
            'importer' => DuplicateFailEnumImporter::class,
        ])->assertFailed();
    }

    public function test_last_wins_duplicate_strategy_replaces_earlier_rows(): void
    {
        DB::table('occupancy_types')->insert([
            ['id' => 1, 'code' => 'shared', 'name' => 'first', 'label' => 'First Label'],
        ]);
        DB::table('legacy_occupancies')->insert([
            ['id' => 1, 'name' => 'second', 'label' => 'Second Label'],
        ]);

        $this->artisan('supercharged-enums:import-enum-using', [
            'importer' => LastWinsDuplicateEnumImporter::class,
        ])->assertSuccessful();

        $contents = file_get_contents($this->tempBasePath . '/app/Enums/LastWinsDuplicateEnum.php');

        expect($contents)->toContain('case Second = 1;')
            ->toContain("self::Second => 'Second Label'")
            ->not->toContain('First Label');
    }

    public function test_existing_enum_prompt_declined_leaves_file_unchanged(): void
    {
        DB::table('occupancy_types')->insert([
            ['id' => 1, 'code' => 'single', 'name' => 'single', 'label' => 'Single Unit'],
        ]);

        $path = $this->tempBasePath . '/app/Enums/SingleTableEnum.php';
        File::put($path, '<?php // original');

        $this->artisan('supercharged-enums:import-enum-using', [
            'importer' => SingleTableEnumImporter::class,
        ])
            ->expectsConfirmation('Enum [SingleTableEnum] already exists at [' . $path . ']. Replace it?', 'no')
            ->assertFailed();

        expect(file_get_contents($path))->toBe('<?php // original');
    }

    public function test_force_overwrites_existing_enum_without_prompt(): void
    {
        DB::table('occupancy_types')->insert([
            ['id' => 1, 'code' => 'single', 'name' => 'single', 'label' => 'Single Unit'],
        ]);

        $path = $this->tempBasePath . '/app/Enums/SingleTableEnum.php';
        File::put($path, '<?php // original');

        $this->artisan('supercharged-enums:import-enum-using', [
            'importer' => SingleTableEnumImporter::class,
            '--force' => true,
        ])->assertSuccessful();

        expect(file_get_contents($path))->toContain('enum SingleTableEnum');
    }

    public function test_no_interaction_fails_when_enum_exists_without_force(): void
    {
        DB::table('occupancy_types')->insert([
            ['id' => 1, 'code' => 'single', 'name' => 'single', 'label' => 'Single Unit'],
        ]);

        File::put($this->tempBasePath . '/app/Enums/SingleTableEnum.php', '<?php // original');

        $this->artisan('supercharged-enums:import-enum-using', [
            'importer' => SingleTableEnumImporter::class,
            '--no-interaction' => true,
        ])->assertFailed();
    }

    private function createOccupancyTypesTable(): void
    {
        Schema::dropIfExists('occupancy_types');

        Schema::create('occupancy_types', function (Blueprint $table): void {
            $table->id();
            $table->string('code');
            $table->string('name');
            $table->string('label');
        });
    }

    private function createLegacyOccupanciesTable(): void
    {
        Schema::dropIfExists('legacy_occupancies');

        Schema::create('legacy_occupancies', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('label');
        });
    }

    private function phpSyntaxIsValid(string $path): bool
    {
        $output = [];
        $exitCode = 0;
        exec('php -l ' . escapeshellarg($path) . ' 2>&1', $output, $exitCode);

        return $exitCode === 0;
    }
}
