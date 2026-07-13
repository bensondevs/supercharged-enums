<?php

declare(strict_types=1);

namespace BensonDevs\SuperchargedEnums\Tests\Fixtures\EnumImporters;

use BensonDevs\SuperchargedEnums\Laravel\Console\Contracts\EnumImporter;
use Illuminate\Database\Query\Builder;

class FilteredQueryEnumImporter extends EnumImporter
{
    public function sources(): array
    {
        return [
            'occupancy_types' => fn (Builder $query): Builder => $query->where('name', 'active'),
        ];
    }
}
