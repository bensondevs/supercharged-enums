<?php

declare(strict_types=1);

namespace BensonDevs\SuperchargedEnums\Support;

use BackedEnum;

final class BackedEnumCaseListing
{
    /**
     * @param  class-string<BackedEnum>  $enumClass
     * @return array<int, string>
     */
    public static function names(string $enumClass): array
    {
        return array_column($enumClass::cases(), column_key: 'name');
    }

    /**
     * @param  class-string<BackedEnum>  $enumClass
     * @return array<int, string|int>
     */
    public static function values(string $enumClass): array
    {
        return array_column($enumClass::cases(), column_key: 'value');
    }
}
