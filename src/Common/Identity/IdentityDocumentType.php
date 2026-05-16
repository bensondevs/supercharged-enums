<?php

declare(strict_types=1);

namespace BensonDevs\SuperchargedEnums\Common\Identity;

use BensonDevs\SuperchargedEnums\EnumExtension;

/**
 * Government-issued or travel identity document kinds used in verification. Case order defines {@see EnumExtension::default()}.
 *
 * {@see self::NationalId} and {@see self::IdCard} may overlap by region; pick the slug that best matches local practice.
 * Backing values are lowercase English slugs.
 */
enum IdentityDocumentType: string
{
    use EnumExtension;

    case Passport = 'passport';

    case NationalId = 'national_id';

    case IdCard = 'id_card';

    case DriversLicense = 'drivers_license';

    case ResidencePermit = 'residence_permit';

    case Visa = 'visa';
}
