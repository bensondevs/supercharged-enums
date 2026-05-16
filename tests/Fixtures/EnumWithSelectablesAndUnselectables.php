<?php

declare(strict_types=1);

namespace BensonDevs\SuperchargedEnums\Tests\Fixtures;

use BensonDevs\SuperchargedEnums\EnumExtension;

enum EnumWithSelectablesAndUnselectables: string
{
    use EnumExtension;

    case Draft = 'draft';

    case Published = 'published';

    case Archived = 'archived';

    /**
     * @return array<self>
     */
    public static function selectables(): array
    {
        return [self::Draft, self::Published];
    }

    /**
     * @return array<self>
     */
    public static function unselectables(): array
    {
        return [self::Published, self::Archived];
    }
}
