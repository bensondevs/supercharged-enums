<?php

declare(strict_types=1);

namespace BensonDevs\SuperchargedEnums\Tests\Fixtures\EnumImporters;

use BensonDevs\SuperchargedEnums\Laravel\Console\Contracts\EnumImporter;

class SingleTableEnumImporter extends EnumImporter
{
    public function sources(): array
    {
        return ['occupancy_types'];
    }
}
