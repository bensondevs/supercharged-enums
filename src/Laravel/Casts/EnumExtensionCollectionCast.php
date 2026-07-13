<?php

declare(strict_types=1);

namespace BensonDevs\SuperchargedEnums\Laravel\Casts;

use BackedEnum;
use BensonDevs\SuperchargedEnums\Laravel\Casts\Concerns\ResolvesBackedEnumJsonArray;
use BensonDevs\SuperchargedEnums\Support\BackedEnumLookup;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Contracts\Database\Eloquent\SerializesCastableAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use ValueError;

/**
 * @template T of BackedEnum
 *
 * @implements CastsAttributes<?Collection<int, T>, iterable<int, mixed>|Collection<int, mixed>>
 */
final class EnumExtensionCollectionCast implements CastsAttributes, SerializesCastableAttributes
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
     * @return ?Collection<int, T>
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): ?Collection
    {
        $data = $this->decodeStoredArray($value);

        if ($data === null) {
            return null;
        }

        /** @var Collection<int, T> */
        return collect($data)->map(function (mixed $item): BackedEnum {
            if ($this->lenient) {
                return $this->findOrDefault($item);
            }

            $case = $this->find($item);

            if ($case === null) {
                throw new ValueError($this->invalidBackingValueMessage($item));
            }

            return $case;
        });
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): ?string
    {
        if ($value === null) {
            return null;
        }

        $backingValues = [];

        foreach ($this->normalizeInputIterable($value) as $item) {
            $backingValues[] = $this->storableBackingValue($item);
        }

        return $this->encodeStoredArray($backingValues);
    }

    /**
     * @param  Collection<int, T>  $value
     * @return array<int, string|int>
     */
    public function serialize(Model $model, string $key, mixed $value, array $attributes): array
    {
        return $value
            ->map(fn (BackedEnum $enum): string | int => $enum->value)
            ->values()
            ->all();
    }

    protected function resolveCaseForSet(string | int $value): ?BackedEnum
    {
        return $this->find($value);
    }

    private function find(mixed $value): ?BackedEnum
    {
        if (method_exists($this->enumClass, 'find')) {
            return $this->enumClass::find($value);
        }

        return BackedEnumLookup::find($this->enumClass, $value);
    }

    private function findOrDefault(mixed $value): BackedEnum
    {
        if (method_exists($this->enumClass, 'findOrDefault')) {
            return $this->enumClass::findOrDefault($value);
        }

        return BackedEnumLookup::findOrDefault($this->enumClass, $value);
    }
}
