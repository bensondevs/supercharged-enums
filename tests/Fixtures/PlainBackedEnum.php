<?php

declare(strict_types=1);

namespace BensonDevs\SuperchargedEnums\Tests\Fixtures;

enum PlainBackedEnum: string
{
    case Something = 'something';
    case Other = 'other';
}
