# Common / Calendar

Calendar dates, weekdays, quarters, and display formats.

Namespace prefix: `BensonDevs\SuperchargedEnums\Common\Calendar\`

## Enums

### `DateDisplayFormat`

`BensonDevs\SuperchargedEnums\Common\Calendar\DateDisplayFormat` · backing: `string` · default: `IsoDisplay` (`iso_display`)

Human-facing date display conventions (not storage or wire formats). `EuropeanNumeric` and `BritishNumeric` share pattern `d/m/Y`; slugs express intent and locale wiring, not a unique pattern per case. `Japanese` uses a best-effort PHP pattern; production Japanese UI may prefer `IntlDateFormatter` with `ja_JP` while storing this case slug as the user preference. Backing values are lowercase English slugs.

| Case | Backing |
|------|--------|
| `IsoDisplay` | `iso_display` |
| `UsNumeric` | `us_numeric` |
| `UsLong` | `us_long` |
| `EuropeanNumeric` | `european_numeric` |
| `EuropeanDots` | `european_dots` |
| `BritishNumeric` | `british_numeric` |
| `ShortMonth` | `short_month` |
| `LongMonth` | `long_month` |
| `Japanese` | `japanese` |

**Enum-specific methods**

```php
use BensonDevs\SuperchargedEnums\Common\Calendar\DateDisplayFormat;

$format = DateDisplayFormat::UsNumeric;

$format->format();  // 'm/d/Y' — PHP date() pattern for this display convention
```

### `DayOfWeek`

`BensonDevs\SuperchargedEnums\Common\Calendar\DayOfWeek` · backing: `string` · default: `Monday` (`monday`)

Weekdays in ISO 8601 order (Monday first). Backing values are lowercase English slugs.

| Case | Backing |
|------|--------|
| `Monday` | `monday` |
| `Tuesday` | `tuesday` |
| `Wednesday` | `wednesday` |
| `Thursday` | `thursday` |
| `Friday` | `friday` |
| `Saturday` | `saturday` |
| `Sunday` | `sunday` |

### `Month`

`BensonDevs\SuperchargedEnums\Common\Calendar\Month` · backing: `string` · default: `January` (`january`)

Gregorian calendar months (January through December). Backing values are lowercase English slugs.

| Case | Backing |
|------|--------|
| `January` | `january` |
| `February` | `february` |
| `March` | `march` |
| `April` | `april` |
| `May` | `may` |
| `June` | `june` |
| `July` | `july` |
| `August` | `august` |
| `September` | `september` |
| `October` | `october` |
| `November` | `november` |
| `December` | `december` |

### `Quarter`

`BensonDevs\SuperchargedEnums\Common\Calendar\Quarter` · backing: `string` · default: `Q1` (`q1`)

Gregorian calendar quarters. Backing values are lowercase slugs q1 through q4.

| Case | Backing |
|------|--------|
| `Q1` | `q1` |
| `Q2` | `q2` |
| `Q3` | `q3` |
| `Q4` | `q4` |

