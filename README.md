# ⚡ Supercharged Enums

Backed enum helpers (`find`, `options`, comparisons, labels) via the `EnumExtension` trait—no framework dependencies. The package also ships optional ready-made enums for everyday domains (HTTP, calendar and time, measurement units, finance, logging, deployment environments, and more) under `BensonDevs\SuperchargedEnums\Common\`, each wired with the same helpers. See [Bundled Common enums](#bundled-common-enums).

**Requirements:** PHP 8.2 or later. The `EnumExtension` trait targets **backed** enums (`string` or `int`). Pure unit enums without a backing type are not supported by lookup normalization.

## Table of contents

- [Installation](#installation)
- [Quick start](#quick-start)
- [Features](#features)
  - [Core helpers](#core-helpers)
  - [Lookup](#lookup)
  - [Case listing](#case-listing)
  - [Naming](#naming)
  - [Select maps](#select-maps)
  - [Comparisons and ordering](#comparisons-and-ordering)
- [Modular composition](#modular-composition)
- [Bundled Common enums](#bundled-common-enums)
- [Development](#development)
- [Support](#support)
- [License](#license)

## Installation

```bash
composer require bensondevs/supercharged-enums
```

## Quick start

Add the trait to your own enum, or use a bundled one from `Common\`—same helpers either way.

**Your enum**

```php
use BensonDevs\SuperchargedEnums\EnumExtension;

enum Status: string
{
    use EnumExtension;

    case Draft = 'draft';
    case Published = 'published';
}
```

**Lookup** — request / DB strings → cases (`null` when unknown)

```php
Status::find('published');               // Status::Published
Status::findOrDefault('archived');         // Status::Draft (falls back to default)
Status::find('published', strict: true);   // Backing values only, no aliases
```

**Labels and forms**

```php
Status::options();                // ['draft' => 'Draft', 'published' => 'Published']
Status::Published->getName();     // "Published"
```

**Comparisons** — declaration order, not backing-value sort

```php
Status::Draft->is('draft');                      // true
Status::Draft->isBefore(Status::Published);      // true
Status::Draft->next();                           // Status::Published
```

**Bundled time units** — [`DurationUnit`](docs/common/Time/README.md) with conversions and the same helpers

```php
use BensonDevs\SuperchargedEnums\Common\Time\DurationUnit;

DurationUnit::find('hour');             // DurationUnit::Hour
DurationUnit::findOrDefault('eon');     // DurationUnit::Second (unknown → default)

DurationUnit::Hour->toSeconds(2);       // 7200
DurationUnit::Hour->isBefore(DurationUnit::Week);                        // true
DurationUnit::Hour->isBetween(DurationUnit::Minute, DurationUnit::Day);  // true
DurationUnit::Hour->next();             // DurationUnit::Day
```

**Bundled measurement units** — [`LengthUnit`](docs/common/Measure/README.md), [`MassUnit`](docs/common/Measure/README.md), and others under [`Common\Measure`](docs/common/Measure/README.md)

```php
use BensonDevs\SuperchargedEnums\Common\Measure\LengthUnit;

LengthUnit::find('mile');               // LengthUnit::Mile
LengthUnit::Mile->toKilometers(1);      // Convert 1 mile → kilometers
LengthUnit::Mile->toMeters(1, decimalDigits: 4);

LengthUnit::Meter->isBefore(LengthUnit::Kilometer);  // true (declaration order)
LengthUnit::Meter->next();              // LengthUnit::Kilometer
```

More domains (HTTP status codes, money rounding, data sizes) are listed under [Bundled Common enums](#bundled-common-enums).

## Features

[`EnumExtension`](src/EnumExtension.php) reference. For a walkthrough, see [Quick start](#quick-start). To use individual traits, see [Modular composition](#modular-composition).

### Core helpers

```php
Status::default();     // First declared case (cases()[0]); override on your enum to return any case
Status::getDefault();  // Alias for default()
Status::random();      // A random declared case
```

`default()` follows **declaration order**, not the smallest or largest backing value. For example, [`HttpStatusCode`](src/Common/Http/HttpStatusCode.php) defaults to `Continue` (100) because it is declared first, even though other codes have smaller numeric semantics in different contexts.

There is no separate config flag: **declare the case you want as the default first**, or **override `default()`** on your enum to return any case you prefer. `findOrDefault()` and any code that falls back to `default()` will use whichever you define.

```php
enum Priority: string
{
    use EnumExtension;

    // Declared first → default() returns Priority::Medium
    case Medium = 'medium';
    case Low = 'low';
    case High = 'high';
}

Priority::default();              // Priority::Medium
Priority::findOrDefault('urgent'); // Priority::Medium (unknown key)

// To change the default, reorder cases — put Low first instead:
enum Priority: string
{
    use EnumExtension;

    case Low = 'low';
    case Medium = 'medium';
    case High = 'high';
}

Priority::default(); // Priority::Low

// Or override default() — keep declaration order, pick any case:
enum Priority: string
{
    use EnumExtension;

    case Medium = 'medium';
    case Low = 'low';
    case High = 'high';

    public static function default(): static
    {
        return self::Low;
    }
}

Priority::default(); // Priority::Low
```

If you use a partial trait stack without `EnumExtension`, define `default()` yourself (for example `return self::cases()[0];`) so `findOrDefault()` keeps working.

### Lookup

```php
Status::find('published');                    // Status::Published — resolve self, backing scalar, or null
Status::find('unknown');                      // null when nothing matches
Status::findOrDefault('unknown');             // Status::Draft — find() or default() when lookup fails
Status::find('published', strict: true);      // Backing values only (no aliases)
```

`find()` accepts:

- An enum instance (returned as-is)
- `null` (returns `null`)
- A backing value: string keys for string-backed enums; int keys for int-backed enums, with numeric strings coerced to int (e.g. `'2'` → `2`)

When `$strict` is `false` and `tryFrom()` does not match, cases may define alternate keys via an instance method:

```php
public function alias(): array
{
    return match ($this) {
        self::Active => ['legacy_active'],
        default => [],
    };
}
```

- Aliases are consulted only when `$strict` is `false`.
- With `$strict` true, resolution is limited to `tryFrom()` (backing values only).
- If the same alias appears on multiple cases, behavior is undefined; the first match in `cases()` iteration order wins.

```php
EnumWithAliases::find('legacy_active');              // Active
EnumWithAliases::find('legacy_active', strict: true); // null
EnumWithAliases::findOrDefault('nope');              // First (default case)
```

### Case listing

```php
Status::names();   // ['Draft', 'Published'] — PHP case names
Status::values();  // ['draft', 'published'] — backing values
```

### Naming

```php
$status = Status::Draft;

$status->getKey();  // 'draft' — the backing value ($this->value)
$status->getName(); // 'Draft' — human label derived from the case name
```

`getName()` normalizes case names for display: underscores and hyphens become spaces, PascalCase is split, and the result is title-cased (`NoShow` → `"No show"`, `FirstOption` → `"First option"`).

### Select maps

Build `value => label` maps for HTML `<select>` elements, JSON APIs, and similar UIs.

```php
Status::options();              // ['draft' => 'Draft', 'published' => 'Published'] — backing value → short label
Status::asSelectOptions();      // Alias for options()
Status::asSelectDescriptions(); // Backing value → longer description text
```

**Label resolution** (`options()`), first match wins:

1. `label()` instance method
2. `getLabel()`
3. `getName()`
4. Raw PHP case `name`

**Description resolution** (`asSelectDescriptions()`):

1. `getDescription()`
2. `getLabel()`
3. Raw PHP case `name`

**Filtering which cases appear in maps**

Define either an allow-list or a deny-list as a static method returning enum cases and/or backing scalars:

```php
public static function selectables(): array
{
    return [self::Beta, 'gamma'];
}

public static function unselectables(): array
{
    return [self::Hidden, 'archived'];
}
```

- When both `selectables()` and `unselectables()` exist, **`selectables()` wins**.
- Filtered cases keep **declaration order** from the enum.

### Comparisons and ordering

Operands accept enum instances, backing scalars, or `null`. Scalars and aliases resolve through `find()` (non-strict), same as lookup.

> **Declaration order, not backing values**
>
> Ordering methods (`compareTo`, `isBefore`, `isAfter`, `next`, `previous`, `min`, `max`, and related helpers) use the index in `cases()`, **not** numeric or lexical order of backing values. A case declared first with backing value `2` is still “before” a case declared second with backing value `1`.

Notes:

- `isIn` / `isNotIn` ignore entries that do not resolve to a case.
- `compareTo` returns `-1`, `0`, `1`, or `null` when the other operand does not resolve.
- `isBetween` is inclusive on both ends by default; pass `includeStart: false` or `includeEnd: false` for exclusive bounds.
- `next` / `previous` return `null` at the end of the list unless `$wrap` is `true`.
- `min` / `max` skip unresolvable operands; return `null` if none resolve.

```php
use BensonDevs\SuperchargedEnums\Common\Calendar\Month;

$month = Month::March;
```

**Equality**

```php
// Backing string (or other scalar the enum resolves)
$month->is('march');                           // true
$month->isNot('december');                     // true

// Enum instance
$month->is(Month::March);                      // true
$month->isNot(Month::December);                // true
```

```php
$month->isIn(['october', 'march', 'june']);    // true — backing values in the list
$month->isIn([Month::October, Month::March]);   // true — cases in the list
$month->isNotIn(['december']);                 // true
$month->isNotIn([Month::December]);            // true
```

**Order** — declaration order, not backing-value sort

```php
// Backing string
$month->isBefore('october');                   // true
$month->isAfter('october');                    // false

// Enum instance
$month->isBefore(Month::October);              // true
$month->isAfter(Month::October);               // false
$month->isBeforeOrEqual(Month::October);       // true
$month->isAfterOrEqual(Month::October);        // false
$month->compareTo('october');                  // -1, 0, 1, or null when other does not resolve
$month->compareTo(Month::October);             // -1
```

**Range** — reversed bounds are swapped automatically

```php
$month->isBetween('january', 'december');                    // true (inclusive both ends)
$month->isBetween(Month::January, Month::December);          // true
$month->isBetween(Month::December, Month::January, includeStart: false); // exclusive start
```

**Position**

```php
$month->isFirst();                             // false
$month->isLast();                              // false
$month->diff('june');                          // 3
$month->diff(Month::June);                     // 3
```

**Navigation** — `null` at the end unless `$wrap` is `true`

```php
$month->next();                                // Month::April
Month::January->previous(wrap: true);          // Month::December (wrap from first)
```

**Aggregates** — skip unresolvable operands; `null` if none resolve

```php
Month::min(Month::March, Month::October);      // Month::March
Month::min('march', 'october');                // Month::March
Month::max(Month::March, 'october');           // Month::October
```

## Modular composition

Individual concerns live under [`src/Concerns/`](src/Concerns/) and can be used without the full trait:

```php
use BensonDevs\SuperchargedEnums\Concerns\EnumComparisons;
use BensonDevs\SuperchargedEnums\Concerns\EnumLookup;

enum Direction: string
{
    use EnumComparisons;
    use EnumLookup;

    case Left = 'left';
    case Right = 'right';
}
```

**Caveat:** `findOrDefault()` calls `default()`, which is defined on `EnumExtension`, not on `EnumLookup` alone. With a partial stack, use `find($key) ?? self::cases()[0]` or add your own `default()` helper.

## 🔋 Bundled Common enums

Optional backed enums under `BensonDevs\SuperchargedEnums\Common\`. Each uses [`EnumExtension`](#features). Unless noted in the domain docs, `default()` is the first declared case; see [Core helpers](#core-helpers) to override.

Per-enum case lists and domain-specific methods are documented under [docs/common/](docs/common/README.md).

| Domain | Documentation | Enums |
|--------|-----------------|-------|
| Angle | [Angle](docs/common/Angle/README.md) | `AngleUnit` |
| Application | [Application](docs/common/Application/README.md) | `DeploymentEnvironment` |
| Calendar | [Calendar](docs/common/Calendar/README.md) | `DateDisplayFormat`, `DayOfWeek`, `Month`, `Quarter` |
| Cryptography | [Cryptography](docs/common/Cryptography/README.md) | `HashAlgorithm` |
| Data size | [DataSize](docs/common/DataSize/README.md) | `BinaryDataSizeUnit`, `DecimalDataSizeUnit` |
| Database | [Database](docs/common/Database/README.md) | `DatabaseEngine` |
| Device | [Device](docs/common/Device/README.md) | `DeviceType` |
| Finance | [Finance](docs/common/Finance/README.md) | `CardBrand`, `MoneyRoundingMode` |
| Geography | [Geography](docs/common/Geography/README.md) | `CompassDirection`, `Hemisphere` |
| HTTP | [Http](docs/common/Http/README.md) | `HttpMethod`, `HttpStatusCode` |
| Identity | [Identity](docs/common/Identity/README.md) | `IdentityDocumentType` |
| Logging | [Logging](docs/common/Logging/README.md) | `LogLevel` |
| Measure | [Measure](docs/common/Measure/README.md) | `AreaUnit`, `EnergyUnit`, `FrequencyUnit`, `LengthUnit`, `MassUnit`, `PowerUnit`, `PressureUnit`, `SpeedUnit`, `TemperatureUnit`, `VolumeUnit` |
| MIME | [Mime](docs/common/Mime/README.md) | `MediaTypeClass` |
| Platform | [Platform](docs/common/Platform/README.md) | `CpuArchitecture`, `OperatingSystemFamily` |
| Text | [Text](docs/common/Text/README.md) | `TextCasing`, `TextTransform` |
| Time | [Time](docs/common/Time/README.md) | `DurationUnit`, `Season`, `TimePrecision` |

### Usage examples

```php
use BensonDevs\SuperchargedEnums\Common\Finance\MoneyRoundingMode;
use BensonDevs\SuperchargedEnums\Common\Measure\LengthUnit;
use BensonDevs\SuperchargedEnums\Common\Time\DurationUnit;

LengthUnit::Mile->toKilometers(1);
DurationUnit::Hour->toSeconds(2);
MoneyRoundingMode::HalfEven->roundMoney(10.005);
```

Behavior is covered by the [Pest test suite](tests/).

## Development

```bash
composer test   # run Pest tests
composer lint   # run Laravel Pint
```

## Support

If this package saves you time, consider supporting its maintenance:

- [GitHub Sponsors](https://github.com/sponsors/bensondevs)
- [Trakteer](https://trakteer.id/bensonsimeon/tip)

Thank you for helping keep Supercharged Enums maintained.

## License

MIT
