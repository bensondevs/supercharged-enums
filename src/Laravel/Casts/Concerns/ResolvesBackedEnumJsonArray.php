<?php

declare(strict_types=1);

namespace BensonDevs\SuperchargedEnums\Laravel\Casts\Concerns;

use BackedEnum;
use BensonDevs\SuperchargedEnums\SuperchargedEnum;
use Illuminate\Support\Collection;
use ValueError;

trait ResolvesBackedEnumJsonArray
{
    /** @var class-string<BackedEnum> */
    private readonly string $enumClass;

    private readonly bool $lenient;

    /**
     * @param  string  ...$arguments  Enum class and optional "lenient" flag from Laravel cast resolution.
     */
    public function __construct(string ...$arguments)
    {
        if ($arguments === []) {
            throw new \InvalidArgumentException(sprintf('%s requires an enum class.', static::class));
        }

        $this->enumClass = $arguments[0];
        $this->lenient = in_array('lenient', $arguments, true);

        if (! enum_exists($this->enumClass)) {
            throw new \InvalidArgumentException(sprintf('%s is not an enum.', $this->enumClass));
        }

        $reflection = new \ReflectionEnum($this->enumClass);

        if ($reflection->getBackingType() === null) {
            throw new \InvalidArgumentException(sprintf('%s must be a backed enum.', $this->enumClass));
        }
    }

    /**
     * @return ?array<int, mixed>
     */
    protected function decodeStoredArray(mixed $value): ?array
    {
        if ($value === null) {
            return null;
        }

        if (is_array($value)) {
            return $value;
        }

        if (! is_string($value)) {
            return null;
        }

        $decoded = json_decode($value, true);

        if (json_last_error() !== JSON_ERROR_NONE || ! is_array($decoded)) {
            return null;
        }

        return $decoded;
    }

    /**
     * @param  array<int, string|int>  $backingValues
     */
    protected function encodeStoredArray(array $backingValues): string
    {
        if (class_exists(\Illuminate\Support\Json::class)) {
            return \Illuminate\Support\Json::encode($backingValues);
        }

        return json_encode($backingValues, JSON_THROW_ON_ERROR);
    }

    protected function normalizeInputIterable(mixed $value): iterable
    {
        if ($value instanceof Collection) {
            return $value;
        }

        if (is_array($value)) {
            return $value;
        }

        throw new ValueError(sprintf(
            'Expected array or Collection, got %s.',
            get_debug_type($value),
        ));
    }

    protected function storableBackingValue(mixed $item, bool $allowSuperchargedEnum = false): string | int
    {
        if ($allowSuperchargedEnum && $item instanceof SuperchargedEnum) {
            $item = $item->unwrap();
        }

        if ($item instanceof BackedEnum) {
            if (! $item instanceof $this->enumClass) {
                throw new ValueError(sprintf(
                    'Expected instance of %s, got %s.',
                    $this->enumClass,
                    $item::class,
                ));
            }

            return $item->value;
        }

        if (! is_string($item) && ! is_int($item)) {
            throw new ValueError(sprintf(
                'Expected enum instance or backing scalar, got %s.',
                get_debug_type($item),
            ));
        }

        $resolved = $this->resolveCaseForSet($item);

        if ($resolved === null) {
            throw new ValueError(sprintf(
                '"%s" is not a valid backing value for enum %s',
                (string) $item,
                $this->enumClass,
            ));
        }

        return $resolved->value;
    }

    abstract protected function resolveCaseForSet(string | int $value): ?BackedEnum;

    protected function invalidBackingValueMessage(mixed $value): string
    {
        return sprintf(
            '"%s" is not a valid backing value for enum %s',
            is_scalar($value) ? (string) $value : get_debug_type($value),
            $this->enumClass,
        );
    }
}
