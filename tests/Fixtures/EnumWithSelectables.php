<?php

declare(strict_types=1);

namespace BensonDevs\SuperchargedEnums\Tests\Fixtures;

use BensonDevs\SuperchargedEnums\EnumExtension;

enum EnumWithSelectables: string
{
    use EnumExtension;

    case Alpha = 'alpha';

    case Beta = 'beta';

    case Gamma = 'gamma';

    /**
     * @return array<self|string>
     */
    public static function selectables(): array
    {
        return [self::Beta, 'gamma'];
    }
}
