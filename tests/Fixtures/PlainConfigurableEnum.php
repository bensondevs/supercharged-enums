<?php

declare(strict_types=1);

namespace BensonDevs\SuperchargedEnums\Tests\Fixtures;

enum PlainConfigurableEnum: string
{
    case FirstDefault = 'first';
    case Second = 'second';
    case Hidden = 'hidden';
    case Anything = 'anything';
}
