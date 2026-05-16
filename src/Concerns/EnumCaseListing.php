<?php

declare(strict_types=1);

namespace BensonDevs\SuperchargedEnums\Concerns;

trait EnumCaseListing
{
    public static function names(): array
    {
        return array_column(self::cases(), column_key: 'name');
    }

    public static function values(): array
    {
        return array_column(self::cases(), column_key: 'value');
    }
}
