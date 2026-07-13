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
use BensonDevs\SuperchargedEnums\SuperchargedEnumType;
use function BensonDevs\SuperchargedEnums\supercharge;

supercharge(VendorStatus::Open)->is('open');
supercharge(VendorStatus::class)->find('open');
supercharge($case)->unwrap(); // when a native enum is required
```

Configure defaults and select lists at bootstrap when the enum lacks `EnumExtension`:

```php
// AppServiceProvider::boot()
supercharge(VendorStatus::class)->configureUsing(
    fn (SuperchargedEnumType $type) => $type
        ->setDefault(VendorStatus::Closed)
        ->setSelectables([VendorStatus::Open, 'closed'])
);

supercharge(VendorStatus::class)->default(); // configured default
supercharge(VendorStatus::class)->all();     // configured selectables only
```

Runtime configuration overrides native enum methods when both exist.

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

### JSON array columns

```php
use BensonDevs\SuperchargedEnums\Laravel\Casts\EnumExtensionCollectionCast;
use BensonDevs\SuperchargedEnums\Laravel\Casts\SuperchargedEnumArrayCast;

protected function casts(): array
{
    return [
        'statuses' => EnumExtensionCollectionCast::of(Status::class),
        'legacy_statuses' => EnumExtensionCollectionCast::of(Status::class, lenient: true),
        'permissions' => SuperchargedEnumArrayCast::of(VendorPermission::class),
        'permissions' => supercharge(VendorPermission::class)->arrayCast(),
    ];
}

// $order->statuses[0]->is('open') — native enum (Collection on read)
// $order->permissions[0]->is('read') — SuperchargedEnum wrapper (array on read)
// $order->toArray()['statuses'] — ['open', 'closed']
```

- **EnumExtensionCollectionCast** — for enums you own with `EnumExtension`; uses `find()` / alias / `findOrDefault()` per element.
- **SuperchargedEnumArrayCast** — for vendor enums; same strict/lenient semantics as `SuperchargedEnumCast`.
- **Strict (default):** invalid DB value throws on read (like Laravel's enum cast).
- **Lenient:** `lenient: true` uses `findOrDefault()` on read for legacy bad data.
- **Writes** always validate — invalid assignment throws.

## Import from legacy lookup tables (beta)

**Beta:** These import commands are experimental and may change in minor releases.

Generate a backed enum with `EnumExtension` from an existing database table:

```bash
php artisan supercharged-enums:import-from-table order_statuses --class=OrderStatus
```

Useful flags:

- `--string` / `--int` — override auto backing detection
- `--value-column=` / `--label-column=` — custom column mapping
- `--aliases` — emit `alias()` for legacy integer IDs (string-backed) or string keys (int-backed)
- `--no-labels` — skip `getLabel()` even when a label column exists
- `--path=app/Enums` — output directory (namespace is derived from the path)

Default column detection: value from `slug`, `code`, `name`, or `id`; labels from `label`, `title`, or `description`. Shows a progress bar and reports unique cases found. This feature is currently beta.

## Enum importers (beta)

**Beta:** These import commands are experimental and may change in minor releases.

For multi-table or repeatable imports, scaffold an importer:

```bash
php artisan make:enum-importer OccupancyEnumImporter
php artisan supercharged-enums:import-enum-using OccupancyEnumImporter
```

Importer methods:

- `sources()` — tables, optionally `table => fn (Builder $q) => $q->where(...)`
- `resolveUsing()` — per-table `fn (array $attributes) => ['value' => $attributes['code'], 'name' => $attributes['code'], 'label' => $attributes['name'], ...]`
- `as()` — optional enum class name override
- `onDuplicate()` — `fail` (default) or `last-wins`
- `aliases()` — emit `alias()` for legacy keys

Default resolver reads `$attributes['id']`, `$attributes['name']`, `$attributes['label']`.

Shows a progress bar while importing and reports unique cases found. Existing enum files prompt before overwrite; `--force` overwrites, `--no-interaction` fails if the file exists. This feature is currently beta.

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
Status::all();                    // [Status::Draft, Status::Published] — filtered cases
Status::collect();                // Illuminate\Support\Collection of filtered cases

Status::Published->getKey();        // 'draft' — backing value
Status::Published->getName();       // 'Published' — title-cased case name
```

`collect()` requires `illuminate/support`.

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
- `EnumSelectMaps` — `options()`, `asSelectDescriptions()`, `all()`, `collect()`, `filteredCases()`
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
