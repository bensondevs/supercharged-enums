<?php

declare(strict_types=1);

namespace BensonDevs\SuperchargedEnums\Tests\Fixtures\EnumImporters;

use BensonDevs\SuperchargedEnums\Laravel\Console\Contracts\EnumImporter;

class MultiTableEnumImporter extends EnumImporter
{
    public function sources(): array
    {
        return [
            'occupancy_types',
            'legacy_occupancies',
        ];
    }
}
