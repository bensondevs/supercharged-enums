<?php

declare(strict_types=1);

namespace BensonDevs\SuperchargedEnums\Tests\Laravel\Console;

use BensonDevs\SuperchargedEnums\SuperchargedEnumsServiceProvider;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Orchestra\Testbench\TestCase;

class ImportEnumFromTableCommandTest extends TestCase
{
    private string $tempBasePath;

    protected function setUp(): void
    {
        $this->tempBasePath = sys_get_temp_dir() . '/supercharged-enums-' . uniqid('', true);

        parent::setUp();

        File::ensureDirectoryExists($this->tempBasePath . '/app/Enums');
        $this->app->setBasePath($this->tempBasePath);
    }

    protected function tearDown(): void
    {
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

    public function test_imports_string_backed_enum_with_labels(): void
    {
        $this->createOrderStatusesTable();
        DB::table('order_statuses')->insert([
            ['id' => 1, 'name' => 'draft', 'label' => 'Draft'],
            ['id' => 2, 'name' => 'shipped', 'label' => 'Shipped'],
        ]);

        $this->artisan('supercharged-enums:import-from-table', [
            'table' => 'order_statuses',
            '--class' => 'OrderStatus',
        ])
            ->expectsOutputToContain('Found 2 unique cases from 2 rows.')
            ->expectsOutputToContain('Unique cases')
            ->assertSuccessful();

        $path = $this->tempBasePath . '/app/Enums/OrderStatus.php';
        expect(file_exists($path))->toBeTrue();

        $contents = file_get_contents($path);
        expect($contents)->toContain('enum OrderStatus: string')
            ->toContain("case Draft = 'draft';")
            ->toContain("case Shipped = 'shipped';")
            ->toContain('use EnumExtension;')
            ->toContain("self::Draft => 'Draft'")
            ->toContain("self::Shipped => 'Shipped'")
            ->not->toContain('alias(): array');

        expect($this->enumSyntaxIsValid($path))->toBeTrue();
    }

    public function test_imports_int_backed_enum_with_aliases(): void
    {
        $this->createLegacyStatusesTable();
        DB::table('legacy_statuses')->insert([
            ['id' => 1, 'name' => 'draft', 'label' => 'Draft'],
            ['id' => 2, 'name' => 'shipped', 'label' => 'Shipped'],
        ]);

        $this->artisan('supercharged-enums:import-from-table', [
            'table' => 'legacy_statuses',
            '--class' => 'LegacyStatus',
            '--value-column' => 'id',
            '--int' => true,
            '--aliases' => true,
        ])->assertSuccessful();

        $path = $this->tempBasePath . '/app/Enums/LegacyStatus.php';
        $contents = file_get_contents($path);

        expect($contents)->toContain('enum LegacyStatus: int')
            ->toContain('case Draft = 1;')
            ->toContain('case Shipped = 2;')
            ->toContain("self::Draft => ['draft']")
            ->toContain("self::Shipped => ['shipped']");

        expect($this->enumSyntaxIsValid($path))->toBeTrue();
    }

    public function test_supports_custom_label_and_value_columns(): void
    {
        Schema::create('workflow_states', function (Blueprint $table): void {
            $table->id();
            $table->string('code');
            $table->string('title');
        });

        DB::table('workflow_states')->insert([
            ['id' => 1, 'code' => 'open', 'title' => 'Open'],
            ['id' => 2, 'code' => 'closed', 'title' => 'Closed'],
        ]);

        $this->artisan('supercharged-enums:import-from-table', [
            'table' => 'workflow_states',
            '--class' => 'WorkflowState',
            '--value-column' => 'code',
            '--label-column' => 'title',
        ])->assertSuccessful();

        $contents = file_get_contents($this->tempBasePath . '/app/Enums/WorkflowState.php');

        expect($contents)->toContain("case Open = 'open';")
            ->toContain("self::Open => 'Open'")
            ->toContain("self::Closed => 'Closed'");
    }

    public function test_string_flag_overrides_auto_detection(): void
    {
        $this->createLegacyStatusesTable();
        DB::table('legacy_statuses')->insert([
            ['id' => 1, 'name' => 'draft', 'label' => 'Draft'],
        ]);

        $this->artisan('supercharged-enums:import-from-table', [
            'table' => 'legacy_statuses',
            '--class' => 'ForcedStringStatus',
            '--value-column' => 'id',
            '--string' => true,
        ])->assertSuccessful();

        $contents = file_get_contents($this->tempBasePath . '/app/Enums/ForcedStringStatus.php');

        expect($contents)->toContain('enum ForcedStringStatus: string')
            ->toContain("case Draft = '1';");
    }

    public function test_fails_when_table_is_empty(): void
    {
        $this->createOrderStatusesTable();

        $this->artisan('supercharged-enums:import-from-table', [
            'table' => 'order_statuses',
            '--class' => 'OrderStatus',
        ])->assertFailed();
    }

    public function test_fails_when_output_file_exists_without_force(): void
    {
        $this->createOrderStatusesTable();
        DB::table('order_statuses')->insert([
            ['id' => 1, 'name' => 'draft', 'label' => 'Draft'],
        ]);

        $path = $this->tempBasePath . '/app/Enums/OrderStatus.php';
        File::put($path, '<?php');

        $this->artisan('supercharged-enums:import-from-table', [
            'table' => 'order_statuses',
            '--class' => 'OrderStatus',
        ])->assertFailed();
    }

    public function test_string_backed_enum_can_emit_id_aliases(): void
    {
        $this->createOrderStatusesTable();
        DB::table('order_statuses')->insert([
            ['id' => 10, 'name' => 'draft', 'label' => 'Draft'],
        ]);

        $this->artisan('supercharged-enums:import-from-table', [
            'table' => 'order_statuses',
            '--class' => 'OrderStatus',
            '--aliases' => true,
        ])->assertSuccessful();

        $contents = file_get_contents($this->tempBasePath . '/app/Enums/OrderStatus.php');

        expect($contents)->toContain('self::Draft => [10]');
    }

    private function createOrderStatusesTable(): void
    {
        Schema::dropIfExists('order_statuses');

        Schema::create('order_statuses', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('label');
        });
    }

    private function createLegacyStatusesTable(): void
    {
        Schema::dropIfExists('legacy_statuses');

        Schema::create('legacy_statuses', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('label');
        });
    }

    private function enumSyntaxIsValid(string $path): bool
    {
        $output = [];
        $exitCode = 0;

        exec('php -l ' . escapeshellarg($path) . ' 2>&1', $output, $exitCode);

        return $exitCode === 0;
    }
}
