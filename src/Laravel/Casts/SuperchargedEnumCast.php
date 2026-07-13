<?php

declare(strict_types=1);

namespace BensonDevs\SuperchargedEnums\Laravel\Casts;

use BackedEnum;
use BensonDevs\SuperchargedEnums\SuperchargedEnum;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Contracts\Database\Eloquent\SerializesCastableAttributes;
use Illuminate\Database\Eloquent\Model;
use ValueError;

use function BensonDevs\SuperchargedEnums\supercharge;

final class SuperchargedEnumCast implements CastsAttributes, SerializesCastableAttributes
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
            throw new \InvalidArgumentException('SuperchargedEnumCast requires an enum class.');
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
     * @param  class-string<BackedEnum>  $enumClass
     */
    public static function of(string $enumClass, bool $lenient = false): string
    {
        return self::class . ':' . $enumClass . ($lenient ? ',lenient' : '');
    }

    public function get(Model $model, string $key, mixed $value, array $attributes): ?SuperchargedEnum
    {
        if ($value === null) {
            return null;
        }

        $type = supercharge($this->enumClass);

        if ($this->lenient) {
            return supercharge($type->findOrDefault($value));
        }

        $case = $type->find($value);

        if ($case === null) {
            throw new ValueError(sprintf(
                '"%s" is not a valid backing value for enum %s',
                is_scalar($value) ? (string) $value : get_debug_type($value),
                $this->enumClass,
            ));
        }

        return supercharge($case);
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof SuperchargedEnum) {
            return $value->unwrap()->value;
        }

        if ($value instanceof BackedEnum) {
            if (! $value instanceof $this->enumClass) {
                throw new ValueError(sprintf(
                    'Expected instance of %s, got %s.',
                    $this->enumClass,
                    $value::class,
                ));
            }

            return $value->value;
        }

        $resolved = supercharge($this->enumClass)->find($value);

        if ($resolved === null) {
            throw new ValueError(sprintf(
                '"%s" is not a valid backing value for enum %s',
                is_scalar($value) ? (string) $value : get_debug_type($value),
                $this->enumClass,
            ));
        }

        return $resolved->value;
    }

    public function serialize(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if ($value instanceof SuperchargedEnum) {
            return $value->unwrap()->value;
        }

        return $value;
    }
}
