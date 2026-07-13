<?php

declare(strict_types=1);

namespace BensonDevs\SuperchargedEnums\Laravel\Console\Contracts;

use Closure;
use Illuminate\Database\Query\Builder;

abstract class EnumImporter
{
    /**
     * @return array<int|string, string|Closure(Builder): Builder>
     */
    abstract public function sources(): array;

    /**
     * @return array<string, Closure(array<string, mixed>): array<string, mixed>>
     */
    public function resolveUsing(): array
    {
        return [];
    }

    public function as(): ?string
    {
        return null;
    }

    public function onDuplicate(): string
    {
        return 'fail';
    }

    public function connection(): ?string
    {
        return null;
    }

    public function aliases(): bool
    {
        return false;
    }

    /**
     * @param  array<string, mixed>  $attributes
     * @return array<string, mixed>
     */
    public function resolveRowFor(string $table, array $attributes): array
    {
        $resolvers = $this->resolveUsing();
        $resolver = $resolvers[$table] ?? $this->defaultResolver(...);

        return $resolver($attributes);
    }

    /**
     * @param  array<string, mixed>  $attributes
     * @return array<string, mixed>
     */
    protected function defaultResolver(array $attributes): array
    {
        return [
            'id' => $attributes['id'],
            'value' => $attributes['id'],
            'name' => $attributes['name'],
            'label' => $attributes['label'] ?? null,
            'sort' => $attributes['id'],
        ];
    }
}
