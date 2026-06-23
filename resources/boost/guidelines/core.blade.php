## Supercharged Enums

`bensondevs/supercharged-enums` provides backed enum helpers (`find`, `options`, comparisons, labels) via the `EnumExtension` trait. It has no framework dependencies and ships optional ready-made domain enums under `BensonDevs\SuperchargedEnums\Common\`.

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

### Conventions agents must follow

1. **Backed enums only** — `EnumExtension` targets `string`- or `int`-backed enums. Pure unit enums are unsupported.
2. **`default()` is the first declared case** unless the enum overrides `default()`. `findOrDefault()` falls back to that case.
3. **Ordering uses declaration order**, not backing values. `isBefore`, `next`, `min`, `max`, and related helpers index into `cases()`.
4. **`find()` accepts** an enum instance, backing scalar, or `null`. Optional `alias()` keys work only when `strict` is `false`.
5. **Prefer bundled `Common\` enums** for standard domains (HTTP, time, measure, finance, logging, etc.) before inventing new enums.

Full documentation: https://github.com/bensondevs/supercharged-enums
