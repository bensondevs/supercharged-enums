<?php

declare(strict_types=1);

namespace BensonDevs\SuperchargedEnums\Common\Cryptography;

use BensonDevs\SuperchargedEnums\EnumExtension;

/**
 * Common cryptographic hash algorithm identifiers. Case order defines {@see EnumExtension::default()}.
 *
 * Backing values are lowercase English slugs (names only; no hashing implementation).
 */
enum HashAlgorithm: string
{
    use EnumExtension;

    case Md5 = 'md5';

    case Sha1 = 'sha1';

    case Sha256 = 'sha256';

    case Sha384 = 'sha384';

    case Sha512 = 'sha512';

    case Sha3_256 = 'sha3_256';

    case Sha3_512 = 'sha3_512';

    case Blake2b = 'blake2b';
}
