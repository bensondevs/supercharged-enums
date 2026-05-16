# Common / Time

Durations, seasons, and sub-second precision labels.

Namespace prefix: `BensonDevs\SuperchargedEnums\Common\Time\`

## Enums

### `DurationUnit`

`BensonDevs\SuperchargedEnums\Common\Time\DurationUnit` · backing: `string` · default: `Second` (`second`)

Wall-clock style duration step sizes, smallest to largest. For sub-second precision labels, see [`TimePrecision`](#timeprecision). Backing values are lowercase English slugs.

| Case | Backing |
|------|--------|
| `Second` | `second` |
| `Minute` | `minute` |
| `Hour` | `hour` |
| `Day` | `day` |
| `Week` | `week` |

**Enum-specific methods**

```php
use BensonDevs\SuperchargedEnums\Common\Time\DurationUnit;

$duration = DurationUnit::Hour;

$duration->toSeconds(2);                        // Convert to seconds; returns int (7200)
$duration->toMinutes(1);                        // Convert a quantity in this enum to minutes (float)
$duration->toHours(24);                         // Convert a quantity in this enum to hours
$duration->toDays(1);                           // Convert a quantity in this enum to days
$duration->toWeeks(1, decimalDigits: 4);        // Convert a quantity in this enum to weeks
```

### `Season`

`BensonDevs\SuperchargedEnums\Common\Time\Season` · backing: `string` · default: `Spring` (`spring`)

Meteorological seasons in declaration order. Backing values are lowercase English slugs (`Autumn` uses `autumn`, not `fall`).

| Case | Backing |
|------|--------|
| `Spring` | `spring` |
| `Summer` | `summer` |
| `Autumn` | `autumn` |
| `Winter` | `winter` |

### `TimePrecision`

`BensonDevs\SuperchargedEnums\Common\Time\TimePrecision` · backing: `string` · default: `Second` (`second`)

Time measurement precision steps, coarsest to finest. Backing values are lowercase English slugs.

| Case | Backing |
|------|--------|
| `Second` | `second` |
| `Millisecond` | `millisecond` |
| `Microsecond` | `microsecond` |
| `Nanosecond` | `nanosecond` |

**Enum-specific methods**

```php
use BensonDevs\SuperchargedEnums\Common\Time\TimePrecision;

$precision = TimePrecision::Millisecond;

$precision->toNanoseconds(1);                   // 1_000_000 — exact int (base unit)
$precision->toSeconds(1500);                    // 1.5 — convert a quantity to seconds
$precision->toMilliseconds(1);                  // Convert a quantity in this enum to milliseconds
$precision->toMicroseconds(1, decimalDigits: 4); // Convert a quantity in this enum to microseconds
```

