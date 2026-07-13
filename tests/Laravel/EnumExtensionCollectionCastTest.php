<?php

declare(strict_types=1);

namespace BensonDevs\SuperchargedEnums\Tests\Laravel;

use BensonDevs\SuperchargedEnums\Laravel\Casts\EnumExtensionCollectionCast;
use BensonDevs\SuperchargedEnums\SuperchargedEnumsServiceProvider;
use BensonDevs\SuperchargedEnums\Tests\Fixtures\EnumWithAliases;
use BensonDevs\SuperchargedEnums\Tests\Fixtures\StringSampleEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Orchestra\Testbench\TestCase;
use ValueError;

class EnumExtensionCollectionCastTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::dropIfExists('collection_cast_test_models');

        Schema::create('collection_cast_test_models', function (Blueprint $table): void {
            $table->id();
            $table->json('statuses')->nullable();
        });
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('collection_cast_test_models');

        parent::tearDown();
    }

    protected function getPackageProviders($app): array
    {
        return [
            SuperchargedEnumsServiceProvider::class,
        ];
    }

    public function test_get_resolves_database_json_to_collection_of_native_enums(): void
    {
        $model = new CollectionCastTestModel;
        $model->setRawAttributes(['statuses' => json_encode(['no_show', 'first'])]);

        expect($model->statuses)->toBeInstanceOf(Collection::class)
            ->and($model->statuses)->toHaveCount(2)
            ->and($model->statuses[0])->toBe(StringSampleEnum::NoShow)
            ->and($model->statuses[1])->toBe(StringSampleEnum::FirstOption);
    }

    public function test_set_with_native_enums_stores_json_backing_values(): void
    {
        $model = new CollectionCastTestModel;
        $model->statuses = collect([StringSampleEnum::NoShow, StringSampleEnum::FirstOption]);

        expect($model->getAttributes()['statuses'])->toBe(json_encode(['no_show', 'first']));
    }

    public function test_set_with_array_of_scalars_stores_json_backing_values(): void
    {
        $model = new CollectionCastTestModel;
        $model->statuses = ['no_show', 'first'];

        expect($model->getAttributes()['statuses'])->toBe(json_encode(['no_show', 'first']));
    }

    public function test_set_with_invalid_scalar_throws_value_error(): void
    {
        $model = new CollectionCastTestModel;

        $this->expectException(ValueError::class);

        $model->statuses = ['invalid'];
    }

    public function test_get_strict_invalid_database_value_throws_value_error(): void
    {
        $model = new CollectionCastTestModel;
        $model->setRawAttributes(['statuses' => json_encode(['invalid'])]);

        $this->expectException(ValueError::class);

        $model->statuses;
    }

    public function test_get_lenient_invalid_database_value_returns_default_case(): void
    {
        $model = new LenientCollectionCastTestModel;
        $model->setRawAttributes(['statuses' => json_encode(['invalid'])]);

        expect($model->statuses)->toBeInstanceOf(Collection::class)
            ->and($model->statuses)->toHaveCount(1)
            ->and($model->statuses[0])->toBe(StringSampleEnum::NoShow);
    }

    public function test_get_resolves_aliases_on_read(): void
    {
        $model = new AliasCollectionCastTestModel;
        $model->setRawAttributes(['statuses' => json_encode(['legacy_active'])]);

        expect($model->statuses[0])->toBe(EnumWithAliases::Active);
    }

    public function test_nullable_attribute_round_trips_null(): void
    {
        $model = new CollectionCastTestModel(['statuses' => null]);

        expect($model->statuses)->toBeNull();

        $model->statuses = null;

        expect($model->getAttributes()['statuses'])->toBeNull();
    }

    public function test_empty_array_round_trips_to_empty_collection(): void
    {
        $model = new CollectionCastTestModel;
        $model->setRawAttributes(['statuses' => json_encode([])]);

        expect($model->statuses)->toBeInstanceOf(Collection::class)
            ->and($model->statuses)->toBeEmpty();

        $model->statuses = collect();

        expect($model->getAttributes()['statuses'])->toBe(json_encode([]));
    }

    public function test_serialize_emits_backing_values_in_to_array(): void
    {
        $model = new CollectionCastTestModel;
        $model->setRawAttributes(['statuses' => json_encode(['no_show', 'first'])]);

        expect($model->toArray()['statuses'])->toBe(['no_show', 'first']);
    }

    public function test_cast_rejects_pure_unit_enum(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new EnumExtensionCollectionCast(UnitPlainEnumForCollectionCast::class);
    }
}

class CollectionCastTestModel extends Model
{
    public $timestamps = false;

    protected $table = 'collection_cast_test_models';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'statuses' => EnumExtensionCollectionCast::of(StringSampleEnum::class),
        ];
    }
}

class LenientCollectionCastTestModel extends Model
{
    public $timestamps = false;

    protected $table = 'collection_cast_test_models';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'statuses' => EnumExtensionCollectionCast::of(StringSampleEnum::class, lenient: true),
        ];
    }
}

class AliasCollectionCastTestModel extends Model
{
    public $timestamps = false;

    protected $table = 'collection_cast_test_models';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'statuses' => EnumExtensionCollectionCast::of(EnumWithAliases::class),
        ];
    }
}

enum UnitPlainEnumForCollectionCast
{
    case Only;
}
