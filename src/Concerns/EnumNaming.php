<?php

declare(strict_types=1);

namespace BensonDevs\SuperchargedEnums\Concerns;

use BensonDevs\SuperchargedEnums\Support\BackedEnumNaming;

trait EnumNaming
{
    public function getKey(): string | int
    {
        return BackedEnumNaming::getKey($this);
    }

    public function getName(): string
    {
        return BackedEnumNaming::formatCaseNameForLabel($this->name);
    }
}
