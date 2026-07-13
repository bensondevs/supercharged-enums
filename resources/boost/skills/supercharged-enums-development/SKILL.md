---
name: supercharged-enums-development
description: Build backed PHP enums with EnumExtension — lookup, select maps, comparisons, labels, bundled Common enums, runtime supercharge() for unextended enums, and SuperchargedEnumCast for Laravel models. Use when creating or editing enums, form options, status/state logic, unit conversions, wrapping vendor enums, or casting Eloquent attributes in bensondevs/supercharged-enums.
---

# Supercharged Enums Development

## When to use this skill

Use this skill when working with `bensondevs/supercharged-enums`:

- Creating or editing backed PHP enums (`string` or `int`)
- Resolving request, query, or database strings to enum cases
- Building `<select>` options or API label maps
- Comparing, ordering, or navigating enum cases
- Using bundled domain enums under `BensonDevs\SuperchargedEnums\Common\`
- Wrapping vendor or unextended backed enums with `supercharge()`
- Casting Laravel Eloquent attributes for vendor enums with `SuperchargedEnumCast`

## Requirements

- PHP 8.2+
- **Backed enums only** (`string` or `int` backing). Pure unit enums are not supported.
- No Laravel dependency — works in any PHP project.

## Setup

```php
use BensonDevs\SuperchargedEnums\EnumExtension;

enum Status: string
{
    use EnumExtension;

    case Draft = 'draft';
    case Published = 'published';
}
```

Or use a bundled enum from `BensonDevs\SuperchargedEnums\Common\{Domain}\*`.

For enums you cannot edit (vendor, generated), use runtime wrapping:

```php
use function BensonDevs\SuperchargedEnums\supercharge;

supercharge(VendorStatus::Open)->is('open');
supercharge(VendorStatus::class)->find('open');
supercharge($case)->unwrap(); // when a native enum is required
```

## Laravel Eloquent casting

For model attributes backed by vendor enums:

```php
use BensonDevs\SuperchargedEnums\Laravel\Casts\SuperchargedEnumCast;

protected function casts(): array
{
    return [
        'status' => SuperchargedEnumCast::of(VendorStatus::class),
        'legacy_status' => SuperchargedEnumCast::of(VendorStatus::class, lenient: true),
        'status' => supercharge(VendorStatus::class)->cast(),
    ];
}

// $order->status->is('open')
// $order->status->unwrap() — native enum
// $order->toArray()['status'] — backing scalar
```

- **Strict (default):** invalid DB value throws on read (like Laravel's enum cast).
- **Lenient:** `lenient: true` uses `findOrDefault()` on read for legacy bad data.
- **Writes** always validate — invalid assignment throws.

## Core helpers

```php
Status::default();     // First declared case (cases()[0]); override to pick another
Status::getDefault();  // Alias for default()
Status::random();      // Random declared case
```

Declare the desired default case **first**, or override `default()` on the enum.

## Lookup

```php
Status::find('published');                    // Status::Published
Status::find('unknown');                      // null
Status::findOrDefault('unknown');             // Status::Draft (falls back to default())
Status::find('published', strict: true);      // Backing values only — no aliases
```

`find()` accepts: enum instance (returned as-is), `null` (returns `null`), or a backing scalar. Int-backed enums coerce numeric strings (`'2'` → `2`).

### Optional aliases

Define per-case `alias(): array` for alternate keys (non-strict mode only):

```php
public function alias(): array
{
    return match ($this) {
        self::Active => ['legacy_active'],
        default => [],
    };
}
```

Duplicate aliases across cases are undefined; first match in `cases()` order wins.

## Labels and select maps

```php
Status::options();                // ['draft' => 'Draft', 'published' => 'Published']
Status::asSelectOptions();        // Alias for options()
Status::asSelectDescriptions();   // Backing value → longer description

Status::Published->getKey();        // 'draft' — backing value
Status::Published->getName();       // 'Published' — title-cased case name
```

**Label resolution** (`options()`), first match wins: `label()` → `getLabel()` → `getName()` → raw case name.

**Description resolution** (`asSelectDescriptions()`): `getDescription()` → `getLabel()` → raw case name.

### Filtering select options

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

When both exist, `selectables()` wins. Filtered cases keep declaration order.

## Comparisons and ordering

Operands accept enum instances, backing scalars, or `null` (resolved via non-strict `find()`).

> **Critical:** Ordering uses **declaration order** (`cases()` index), **not** backing-value sort.

```php
Status::Draft->is('draft');                      // true
Status::Draft->isBefore(Status::Published);      // true
Status::Draft->isAfter(Status::Published);       // false
Status::Draft->compareTo(Status::Published);     // -1, 0, 1, or null
Status::Draft->isBetween('draft', 'published');  // inclusive by default
Status::Draft->next();                           // Status::Published
Status::Draft->previous(wrap: true);             // wraps at list ends
Status::min(Status::Draft, Status::Published);   // Status::Draft
Status::max(Status::Draft, Status::Published);   // Status::Published
```

- `isIn` / `isNotIn` ignore unresolvable entries.
- `next` / `previous` return `null` at list ends unless `$wrap` is `true`.
- `min` / `max` skip unresolvable operands; return `null` if none resolve.

## Case listing

```php
Status::names();   // ['Draft', 'Published'] — PHP case names
Status::values();  // ['draft', 'published'] — backing values
```

## Modular composition

Use individual traits from `BensonDevs\SuperchargedEnums\Concerns\` instead of the full `EnumExtension`:

- `EnumLookup` — `find()`, `findOrDefault()`
- `EnumSelectMaps` — `options()`, `asSelectDescriptions()`
- `EnumComparisons` — ordering and navigation
- `EnumNaming` — `getKey()`, `getName()`
- `EnumCaseListing` — `names()`, `values()`

**Caveat:** `findOrDefault()` calls `default()`, which lives on `EnumExtension`. With a partial stack, add your own `default()` or use `find($key) ?? self::cases()[0]`.

## Bundled Common enums

Prefer existing enums before creating new ones:

| Domain | Examples |
|--------|----------|
| HTTP | `HttpMethod`, `HttpStatusCode` |
| Time | `DurationUnit`, `Season`, `TimePrecision` |
| Measure | `LengthUnit`, `MassUnit`, `TemperatureUnit`, … |
| Finance | `MoneyRoundingMode`, `CardBrand` |
| Calendar | `Month`, `DayOfWeek`, `Quarter` |
| Logging | `LogLevel` |
| Application | `DeploymentEnvironment` |

Namespace: `BensonDevs\SuperchargedEnums\Common\{Domain}\{EnumName}`.

Domain-specific methods exist on some enums (e.g. `LengthUnit::Mile->toKilometers(1)`, `DurationUnit::Hour->toSeconds(2)`).

## Anti-patterns

- Do **not** use `isBefore` / `compareTo` / `min` / `max` for backing-value sorting — use declaration order semantics or sort explicitly by `->value`.
- Do **not** assume `default()` is the smallest or lexicographically first backing value.
- Do **not** define duplicate aliases on multiple cases.
- Do **not** use `EnumExtension` on pure (non-backed) unit enums.
