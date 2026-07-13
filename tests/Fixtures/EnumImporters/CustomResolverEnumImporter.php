<?php

declare(strict_types=1);

namespace BensonDevs\SuperchargedEnums\Tests\Fixtures\EnumImporters;

use BensonDevs\SuperchargedEnums\Laravel\Console\Contracts\EnumImporter;

class CustomResolverEnumImporter extends EnumImporter
{
    public function sources(): array
    {
        return ['occupancy_types'];
    }

    public function resolveUsing(): array
    {
        return [
            'occupancy_types' => fn (array $attributes): array => [
                'id' => $attributes['id'],
                'value' => $attributes['code'],
                'name' => $attributes['code'],
                'label' => $attributes['name'],
                'sort' => $attributes['id'],
            ],
        ];
    }
}
