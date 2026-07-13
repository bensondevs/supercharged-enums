<?php

declare(strict_types=1);

namespace BensonDevs\SuperchargedEnums\Laravel\Casts;

use BackedEnum;
use BensonDevs\SuperchargedEnums\Laravel\Casts\Concerns\ResolvesBackedEnumJsonArray;
use BensonDevs\SuperchargedEnums\SuperchargedEnum;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Contracts\Database\Eloquent\SerializesCastableAttributes;
use Illuminate\Database\Eloquent\Model;
use ValueError;

use function BensonDevs\SuperchargedEnums\supercharge;

final class SuperchargedEnumArrayCast implements CastsAttributes, SerializesCastableAttributes
{
    use ResolvesBackedEnumJsonArray;

    /**
     * @param  class-string<BackedEnum>  $enumClass
     */
    public static function of(string $enumClass, bool $lenient = false): string
    {
        return self::class . ':' . $enumClass . ($lenient ? ',lenient' : '');
    }

    /**
     * @return ?array<int, SuperchargedEnum>
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): ?array
    {
        $data = $this->decodeStoredArray($value);

        if ($data === null) {
            return null;
        }

        $type = supercharge($this->enumClass);

        return array_map(function (mixed $item) use ($type): SuperchargedEnum {
            if ($this->lenient) {
                return supercharge($type->findOrDefault($item));
            }

            $case = $type->find($item);

            if ($case === null) {
                throw new ValueError($this->invalidBackingValueMessage($item));
            }

            return supercharge($case);
        }, $data);
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): ?string
    {
        if ($value === null) {
            return null;
        }

        $backingValues = [];

        foreach ($this->normalizeInputIterable($value) as $item) {
            $backingValues[] = $this->storableBackingValue($item, allowSuperchargedEnum: true);
        }

        return $this->encodeStoredArray($backingValues);
    }

    /**
     * @return array<int, string|int>
     */
    public function serialize(Model $model, string $key, mixed $value, array $attributes): array
    {
        return array_map(
            fn (SuperchargedEnum $wrapper): string | int => $wrapper->unwrap()->value,
            $value,
        );
    }

    protected function resolveCaseForSet(string | int $value): ?BackedEnum
    {
        return supercharge($this->enumClass)->find($value);
    }
}
