<?php

declare(strict_types=1);

namespace BensonDevs\SuperchargedEnums\Tests\Fixtures;

use BensonDevs\SuperchargedEnums\EnumExtension;

enum EnumWithUnselectables: string
{
    use EnumExtension;

    case Visible = 'visible';

    case Hidden = 'hidden';

    case AlsoVisible = 'also_visible';

    /**
     * @return array<self|string>
     */
    public static function unselectables(): array
    {
        return [self::Hidden, 'hidden'];
    }
}
