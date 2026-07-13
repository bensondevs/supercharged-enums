## Supercharged Enums

`bensondevs/supercharged-enums` provides backed enum helpers (`find`, `options`, comparisons, labels) via the `EnumExtension` trait, or at runtime via `supercharge()` for enums you cannot modify. It has no framework dependencies and ships optional ready-made domain enums under `BensonDevs\SuperchargedEnums\Common\`.

### Installation

```bash
composer require bensondevs/supercharged-enums
```

### Quick example

```php
use BensonDevs\SuperchargedEnums\EnumExtension;

enum Status: string
{
    use EnumExtension;

    case Draft = 'draft';
    case Published = 'published';
}

Status::find('published');           // Status::Published
Status::findOrDefault('archived');   // Status::Draft
Status::options();                   // ['draft' => 'Draft', 'published' => 'Published']
Status::Draft->isBefore(Status::Published); // true (declaration order)
```

### Runtime supercharge (vendor / unextended enums)

```php
use function BensonDevs\SuperchargedEnums\supercharge;

supercharge(VendorStatus::Open)->is('open');
supercharge(VendorStatus::class)->find('open');
supercharge($case)->unwrap(); // native enum when required
```

### Laravel Eloquent casting

```php
use BensonDevs\SuperchargedEnums\Laravel\Casts\SuperchargedEnumCast;

'status' => SuperchargedEnumCast::of(VendorStatus::class);
// $order->status->is('open'); unwrap() for native enum; toArray() emits backing value
```

### Conventions agents must follow

1. **Backed enums only** — `EnumExtension` targets `string`- or `int`-backed enums. Pure unit enums are unsupported.
2. **`default()` is the first declared case** unless the enum overrides `default()`. `findOrDefault()` falls back to that case.
3. **Ordering uses declaration order**, not backing values. `isBefore`, `next`, `min`, `max`, and related helpers index into `cases()`.
4. **`find()` accepts** an enum instance, backing scalar, or `null`. Optional `alias()` keys work only when `strict` is `false`.
5. **Prefer bundled `Common\` enums** for standard domains (HTTP, time, measure, finance, logging, etc.) before inventing new enums.
6. **Use `supercharge()`** for backed enums you cannot modify (vendor, generated). Pass a case for instance helpers or `SomeEnum::class` for `find()` / `options()`. Call `unwrap()` when a native enum is required.
7. **Use `SuperchargedEnumCast::of()`** on Laravel model attributes for vendor enums — returns `SuperchargedEnum` on read, stores backing value in DB. Strict by default; pass `lenient: true` for legacy columns with invalid data.

Full documentation: https://github.com/bensondevs/supercharged-enums
