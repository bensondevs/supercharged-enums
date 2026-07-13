<?php

declare(strict_types=1);

namespace BensonDevs\SuperchargedEnums\Tests\Laravel;

use BensonDevs\SuperchargedEnums\Laravel\Casts\SuperchargedEnumArrayCast;
use BensonDevs\SuperchargedEnums\SuperchargedEnum;
use BensonDevs\SuperchargedEnums\SuperchargedEnumsServiceProvider;
use BensonDevs\SuperchargedEnums\Tests\Fixtures\PlainBackedEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Orchestra\Testbench\TestCase;
use ValueError;

use function BensonDevs\SuperchargedEnums\supercharge;

class SuperchargedEnumArrayCastTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::dropIfExists('array_cast_test_models');

        Schema::create('array_cast_test_models', function (Blueprint $table): void {
            $table->id();
            $table->json('statuses')->nullable();
        });
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('array_cast_test_models');

        parent::tearDown();
    }

    protected function getPackageProviders($app): array
    {
        return [
            SuperchargedEnumsServiceProvider::class,
        ];
    }

    public function test_get_resolves_database_json_to_array_of_supercharged_enums(): void
    {
        $model = new ArrayCastTestModel;
        $model->setRawAttributes(['statuses' => json_encode(['something', 'other'])]);

        expect($model->statuses)->toBeArray()
            ->and($model->statuses)->toHaveCount(2)
            ->and($model->statuses[0])->toBeInstanceOf(SuperchargedEnum::class)
            ->and($model->statuses[0]->is('something'))->toBeTrue()
            ->and($model->statuses[1]->unwrap())->toBe(PlainBackedEnum::Other);
    }

    public function test_set_with_native_enums_stores_json_backing_values(): void
    {
        $model = new ArrayCastTestModel;
        $model->statuses = [PlainBackedEnum::Something, PlainBackedEnum::Other];

        expect($model->getAttributes()['statuses'])->toBe(json_encode(['something', 'other']));
    }

    public function test_set_with_wrappers_stores_json_backing_values(): void
    {
        $model = new ArrayCastTestModel;
        $model->statuses = [
            supercharge(PlainBackedEnum::Something),
            supercharge(PlainBackedEnum::Other),
        ];

        expect($model->getAttributes()['statuses'])->toBe(json_encode(['something', 'other']));
    }

    public function test_set_with_scalars_stores_json_backing_values(): void
    {
        $model = new ArrayCastTestModel;
        $model->statuses = ['something', 'other'];

        expect($model->getAttributes()['statuses'])->toBe(json_encode(['something', 'other']));
    }

    public function test_set_with_invalid_scalar_throws_value_error(): void
    {
        $model = new ArrayCastTestModel;

        $this->expectException(ValueError::class);

        $model->statuses = ['invalid'];
    }

    public function test_get_strict_invalid_database_value_throws_value_error(): void
    {
        $model = new ArrayCastTestModel;
        $model->setRawAttributes(['statuses' => json_encode(['invalid'])]);

        $this->expectException(ValueError::class);

        $model->statuses;
    }

    public function test_get_lenient_invalid_database_value_returns_default_case(): void
    {
        $model = new LenientArrayCastTestModel;
        $model->setRawAttributes(['statuses' => json_encode(['invalid'])]);

        expect($model->statuses)->toBeArray()
            ->and($model->statuses)->toHaveCount(1)
            ->and($model->statuses[0]->unwrap())->toBe(PlainBackedEnum::Something);
    }

    public function test_nullable_attribute_round_trips_null(): void
    {
        $model = new ArrayCastTestModel(['statuses' => null]);

        expect($model->statuses)->toBeNull();

        $model->statuses = null;

        expect($model->getAttributes()['statuses'])->toBeNull();
    }

    public function test_empty_array_round_trips_to_empty_array(): void
    {
        $model = new ArrayCastTestModel;
        $model->setRawAttributes(['statuses' => json_encode([])]);

        expect($model->statuses)->toBeArray()
            ->and($model->statuses)->toBeEmpty();

        $model->statuses = [];

        expect($model->getAttributes()['statuses'])->toBe(json_encode([]));
    }

    public function test_serialize_emits_backing_values_in_to_array(): void
    {
        $model = new ArrayCastTestModel;
        $model->setRawAttributes(['statuses' => json_encode(['something', 'other'])]);

        expect($model->toArray()['statuses'])->toBe(['something', 'other']);
    }

    public function test_array_cast_helper_on_supercharge_type(): void
    {
        expect(supercharge(PlainBackedEnum::class)->arrayCast())->toBe(
            SuperchargedEnumArrayCast::of(PlainBackedEnum::class),
        );
        expect(supercharge(PlainBackedEnum::class)->arrayCast(lenient: true))->toBe(
            SuperchargedEnumArrayCast::of(PlainBackedEnum::class, lenient: true),
        );
    }

    public function test_cast_rejects_pure_unit_enum(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new SuperchargedEnumArrayCast(UnitPlainEnumForArrayCast::class);
    }
}

class ArrayCastTestModel extends Model
{
    public $timestamps = false;

    protected $table = 'array_cast_test_models';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'statuses' => SuperchargedEnumArrayCast::of(PlainBackedEnum::class),
        ];
    }
}

class LenientArrayCastTestModel extends Model
{
    public $timestamps = false;

    protected $table = 'array_cast_test_models';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'statuses' => SuperchargedEnumArrayCast::of(PlainBackedEnum::class, lenient: true),
        ];
    }
}

enum UnitPlainEnumForArrayCast
{
    case Only;
}
