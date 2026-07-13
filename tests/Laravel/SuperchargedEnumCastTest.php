<?php

declare(strict_types=1);

namespace BensonDevs\SuperchargedEnums\Tests\Laravel;

use BensonDevs\SuperchargedEnums\Laravel\Casts\SuperchargedEnumCast;
use BensonDevs\SuperchargedEnums\SuperchargedEnum;
use BensonDevs\SuperchargedEnums\SuperchargedEnumsServiceProvider;
use BensonDevs\SuperchargedEnums\Tests\Fixtures\PlainBackedEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Orchestra\Testbench\TestCase;
use ValueError;

use function BensonDevs\SuperchargedEnums\supercharge;

class SuperchargedEnumCastTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::dropIfExists('cast_test_models');

        Schema::create('cast_test_models', function (Blueprint $table): void {
            $table->id();
            $table->string('status')->nullable();
        });
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('cast_test_models');

        parent::tearDown();
    }

    protected function getPackageProviders($app): array
    {
        return [
            SuperchargedEnumsServiceProvider::class,
        ];
    }

    public function test_get_resolves_database_value_to_supercharged_enum(): void
    {
        $model = new CastTestModel(['status' => 'something']);

        expect($model->status)->toBeInstanceOf(SuperchargedEnum::class)
            ->and($model->status->is('something'))->toBeTrue()
            ->and($model->status->unwrap())->toBe(PlainBackedEnum::Something);
    }

    public function test_set_with_native_enum_stores_backing_value(): void
    {
        $model = new CastTestModel;
        $model->status = PlainBackedEnum::Something;

        expect($model->getAttributes()['status'])->toBe('something');
    }

    public function test_set_with_wrapper_stores_backing_value(): void
    {
        $model = new CastTestModel;
        $model->status = supercharge(PlainBackedEnum::Something);

        expect($model->getAttributes()['status'])->toBe('something');
    }

    public function test_set_with_scalar_stores_backing_value(): void
    {
        $model = new CastTestModel;
        $model->status = 'something';

        expect($model->getAttributes()['status'])->toBe('something');
    }

    public function test_set_with_invalid_scalar_throws_value_error(): void
    {
        $model = new CastTestModel;

        $this->expectException(ValueError::class);

        $model->status = 'invalid';
    }

    public function test_get_strict_invalid_database_value_throws_value_error(): void
    {
        $model = new CastTestModel;
        $model->setRawAttributes(['status' => 'invalid']);

        $this->expectException(ValueError::class);

        $model->status;
    }

    public function test_get_lenient_invalid_database_value_returns_default_case(): void
    {
        $model = new LenientCastTestModel;
        $model->setRawAttributes(['status' => 'invalid']);

        expect($model->status)->toBeInstanceOf(SuperchargedEnum::class)
            ->and($model->status->unwrap())->toBe(PlainBackedEnum::Something);
    }

    public function test_nullable_attribute_round_trips_null(): void
    {
        $model = new CastTestModel(['status' => null]);

        expect($model->status)->toBeNull();

        $model->status = null;

        expect($model->getAttributes()['status'])->toBeNull();
    }

    public function test_serialize_emits_backing_value_in_to_array(): void
    {
        $model = new CastTestModel(['status' => 'something']);

        expect($model->toArray()['status'])->toBe('something');
    }

    public function test_cast_helper_on_supercharge_type(): void
    {
        expect(supercharge(PlainBackedEnum::class)->cast())->toBe(
            SuperchargedEnumCast::of(PlainBackedEnum::class),
        );
        expect(supercharge(PlainBackedEnum::class)->cast(lenient: true))->toBe(
            SuperchargedEnumCast::of(PlainBackedEnum::class, lenient: true),
        );
    }

    public function test_cast_rejects_pure_unit_enum(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new SuperchargedEnumCast(UnitPlainEnum::class);
    }
}

class CastTestModel extends Model
{
    public $timestamps = false;

    protected $table = 'cast_test_models';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'status' => SuperchargedEnumCast::of(PlainBackedEnum::class),
        ];
    }
}

class LenientCastTestModel extends Model
{
    public $timestamps = false;

    protected $table = 'cast_test_models';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'status' => SuperchargedEnumCast::of(PlainBackedEnum::class, lenient: true),
        ];
    }
}

enum UnitPlainEnum
{
    case Only;
}
