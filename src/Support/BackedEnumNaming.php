<?php

declare(strict_types=1);

namespace BensonDevs\SuperchargedEnums\Support;

use BackedEnum;

final class BackedEnumNaming
{
    public static function getKey(BackedEnum $case): string | int
    {
        return $case->value;
    }

    public static function getName(BackedEnum $case): string
    {
        return self::formatCaseNameForLabel($case->name);
    }

    public static function formatCaseNameForLabel(string $caseName): string
    {
        $normalized = str_replace(['-', '_'], ' ', $caseName);
        $withSpaces = preg_replace('/([a-z])([A-Z])/', '$1 $2', $normalized);
        if (! is_string($withSpaces)) {
            $withSpaces = $normalized;
        }
        $collapsed = preg_replace('/\s+/', ' ', trim($withSpaces));
        if (! is_string($collapsed)) {
            $collapsed = trim($withSpaces);
        }

        return ucfirst(strtolower($collapsed));
    }
}
