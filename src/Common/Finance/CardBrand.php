<?php

declare(strict_types=1);

namespace BensonDevs\SuperchargedEnums\Common\Finance;

use BensonDevs\SuperchargedEnums\EnumExtension;

/**
 * Payment card network identifiers. Case order defines {@see EnumExtension::default()}.
 *
 * Backing values are lowercase English slugs (network names only; no card validation).
 */
enum CardBrand: string
{
    use EnumExtension;

    case Visa = 'visa';

    case Mastercard = 'mastercard';

    case Amex = 'amex';

    case Discover = 'discover';

    case Diners = 'diners';

    case Jcb = 'jcb';

    case Unionpay = 'unionpay';

    case Maestro = 'maestro';

    case CartesBancaires = 'cartes_bancaires';
}
