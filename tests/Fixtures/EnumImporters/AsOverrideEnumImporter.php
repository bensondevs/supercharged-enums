<?php

declare(strict_types=1);

namespace BensonDevs\SuperchargedEnums\Tests\Fixtures\EnumImporters;

use BensonDevs\SuperchargedEnums\Laravel\Console\Contracts\EnumImporter;

class AsOverrideEnumImporter extends EnumImporter
{
    public function sources(): array
    {
        return ['occupancy_types'];
    }

    public function as(): string
    {
        return 'PowerfulEnum';
    }
}
