<?php

declare(strict_types=1);

namespace BensonDevs\SuperchargedEnums\Tests\Fixtures;

enum PlainBackedEnumWithCustomMethod: string
{
    case Alpha = 'alpha';

    public function customLabel(): string
    {
        return 'Custom ' . $this->name;
    }
}
