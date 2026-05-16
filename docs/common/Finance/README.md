# Common / Finance

Payment networks and money rounding modes.

Namespace prefix: `BensonDevs\SuperchargedEnums\Common\Finance\`

## Enums

### `CardBrand`

`BensonDevs\SuperchargedEnums\Common\Finance\CardBrand` · backing: `string` · default: `Visa` (`visa`)

Payment card network identifiers. Backing values are lowercase English slugs (network names only; no card validation).

| Case | Backing |
|------|--------|
| `Visa` | `visa` |
| `Mastercard` | `mastercard` |
| `Amex` | `amex` |
| `Discover` | `discover` |
| `Diners` | `diners` |
| `Jcb` | `jcb` |
| `Unionpay` | `unionpay` |
| `Maestro` | `maestro` |
| `CartesBancaires` | `cartes_bancaires` |

### `MoneyRoundingMode`

`BensonDevs\SuperchargedEnums\Common\Finance\MoneyRoundingMode` · backing: `string` · default: `HalfUp` (`half_up`)

Money and decimal rounding modes. `HalfEven` is banker's rounding. Results use IEEE 754 floats (not arbitrary-precision decimals). Backing values are lowercase English slugs.

| Case | Backing |
|------|--------|
| `HalfUp` | `half_up` |
| `HalfDown` | `half_down` |
| `HalfEven` | `half_even` |
| `Up` | `up` |
| `Down` | `down` |
| `TowardZero` | `toward_zero` |
| `AwayFromZero` | `away_from_zero` |

**Enum-specific methods**

```php
use BensonDevs\SuperchargedEnums\Common\Finance\MoneyRoundingMode;

$mode = MoneyRoundingMode::HalfEven;

$mode->round(2.5);                    // 2.0 — round a float using this mode (decimalPlaces default 0)
$mode->round(3.5);                    // 4.0 — banker's rounding to the nearest even integer
$mode->roundMoney(10.556);            // Round money (decimalPlaces default 2)
$mode->roundMoney(10.556, decimalPlaces: 1); // Override decimal places
```

