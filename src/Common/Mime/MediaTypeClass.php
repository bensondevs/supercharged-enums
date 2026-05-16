<?php

declare(strict_types=1);

namespace BensonDevs\SuperchargedEnums\Common\Mime;

use BensonDevs\SuperchargedEnums\EnumExtension;

/**
 * Top-level MIME media type classes (RFC 6838). Case order defines {@see EnumExtension::default()}.
 *
 * Backing values are lowercase English slugs matching the type name before the slash.
 */
enum MediaTypeClass: string
{
    use EnumExtension;

    case Text = 'text';

    case Image = 'image';

    case Audio = 'audio';

    case Video = 'video';

    case Application = 'application';

    case Multipart = 'multipart';

    case Font = 'font';

    case Message = 'message';
}
