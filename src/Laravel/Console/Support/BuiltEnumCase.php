<?php

declare(strict_types=1);

namespace BensonDevs\SuperchargedEnums\Laravel\Console\Support;

final class BuiltEnumCase
{
    /**
     * @param  array<int, int|string>  $aliases
     */
    public function __construct(
        public readonly string $name,
        public readonly string | int $backingValue,
        public readonly ?string $label = null,
        public readonly array $aliases = [],
    ) {}
}
