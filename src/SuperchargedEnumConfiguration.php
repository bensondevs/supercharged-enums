<?php

declare(strict_types=1);

namespace BensonDevs\SuperchargedEnums;

use BackedEnum;

/**
 * Runtime configuration store for {@see supercharge()} enum classes.
 */
final class SuperchargedEnumConfiguration
{
    /** @var array<class-string<BackedEnum>, self> */
    private static array $configurations = [];

    private ?BackedEnum $default = null;

    /** @var ?array<int, BackedEnum|int|string> */
    private ?array $selectables = null;

    /** @var ?array<int, BackedEnum|int|string> */
    private ?array $unselectables = null;

    /**
     * @param  class-string<BackedEnum>  $enumClass
     */
    private function __construct(private readonly string $enumClass) {}

    /**
     * @param  class-string<BackedEnum>  $enumClass
     */
    public static function for(string $enumClass): self
    {
        return self::$configurations[$enumClass] ??= new self($enumClass);
    }

    public static function reset(): void
    {
        self::$configurations = [];
    }

    public function hasDefault(): bool
    {
        return $this->default !== null;
    }

    public function default(): ?BackedEnum
    {
        return $this->default;
    }

    public function setDefault(BackedEnum $case): void
    {
        if (! $case instanceof $this->enumClass) {
            throw new \InvalidArgumentException(sprintf(
                'Expected instance of %s, got %s.',
                $this->enumClass,
                $case::class,
            ));
        }

        $this->default = $case;
    }

    public function hasSelectables(): bool
    {
        return $this->selectables !== null;
    }

    /**
     * @return ?array<int, BackedEnum|int|string>
     */
    public function selectables(): ?array
    {
        return $this->selectables;
    }

    /**
     * @param  array<int, BackedEnum|int|string>  $entries
     */
    public function setSelectables(array $entries): void
    {
        $this->selectables = $entries;
    }

    public function hasUnselectables(): bool
    {
        return $this->unselectables !== null;
    }

    /**
     * @return ?array<int, BackedEnum|int|string>
     */
    public function unselectables(): ?array
    {
        return $this->unselectables;
    }

    /**
     * @param  array<int, BackedEnum|int|string>  $entries
     */
    public function setUnselectables(array $entries): void
    {
        $this->unselectables = $entries;
    }
}
