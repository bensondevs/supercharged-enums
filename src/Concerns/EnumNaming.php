<?php

declare(strict_types=1);

namespace BensonDevs\SuperchargedEnums\Concerns;

trait EnumNaming
{
    public function getKey(): string|int
    {
        return $this->value;
    }

    public function getName(): string
    {
        return self::formatCaseNameForLabel($this->name);
    }

    private static function formatCaseNameForLabel(string $caseName): string
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
