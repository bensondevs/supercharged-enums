# Common / Data size

Binary (IEC, ×1024) and decimal (SI, ×1000) data-size steps. Conversions use **bits** as the base unit.

Namespace prefix: `BensonDevs\SuperchargedEnums\Common\DataSize\`

## Enums

### `BinaryDataSizeUnit`

`BensonDevs\SuperchargedEnums\Common\DataSize\BinaryDataSizeUnit` · backing: `string` · default: `Bit` (`bit`)

Data size steps using **binary** IEC prefixes: each step above multiplies by **1024**. For powers of 1000 (decimal), use [`DecimalDataSizeUnit`](#decimaldatasizeunit). Backing values are lowercase English slugs.

| Case | Backing |
|------|--------|
| `Bit` | `bit` |
| `Byte` | `byte` |
| `Kibibyte` | `kibibyte` |
| `Mebibyte` | `mebibyte` |
| `Gibibyte` | `gibibyte` |
| `Tebibyte` | `tebibyte` |
| `Pebibyte` | `pebibyte` |
| `Exbibyte` | `exbibyte` |

**Enum-specific methods**

```php
use BensonDevs\SuperchargedEnums\Common\DataSize\BinaryDataSizeUnit;

$size = BinaryDataSizeUnit::Gibibyte;

$size->toBytes(1);                              // Convert a quantity in this enum to bytes (float, default 2 decimals)
$size->toKibibytes(1);                          // Convert a quantity in this enum to kibibytes
$size->toMebibytes(1);                          // Convert a quantity in this enum to mebibytes
$size->toGibibytes(1);                          // Convert a quantity in this enum to gibibytes
$size->toTebibytes(1);                          // Convert a quantity in this enum to tebibytes
$size->toPebibytes(1);                         // Convert a quantity in this enum to pebibytes
$size->toExbibytes(1, decimalDigits: 4);      // Convert a quantity in this enum to exbibytes
$size->toBits(8);                               // Convert to bits (int, from ConvertsDataSizeUnits)
```

### `DecimalDataSizeUnit`

`BensonDevs\SuperchargedEnums\Common\DataSize\DecimalDataSizeUnit` · backing: `string` · default: `Bit` (`bit`)

Data size steps using **decimal** SI-style prefixes: each step above multiplies by **1000**. For powers of 1024 (IEC binary), use [`BinaryDataSizeUnit`](#binarydatasizeunit). Backing values are lowercase English slugs.

| Case | Backing |
|------|--------|
| `Bit` | `bit` |
| `Byte` | `byte` |
| `Kilobyte` | `kilobyte` |
| `Megabyte` | `megabyte` |
| `Gigabyte` | `gigabyte` |
| `Terabyte` | `terabyte` |
| `Petabyte` | `petabyte` |
| `Exabyte` | `exabyte` |

**Enum-specific methods**

```php
use BensonDevs\SuperchargedEnums\Common\DataSize\DecimalDataSizeUnit;

$size = DecimalDataSizeUnit::Gigabyte;

$size->toBytes(1);                              // Convert a quantity in this enum to bytes (float, default 2 decimals)
$size->toKilobytes(1);                          // Convert a quantity in this enum to kilobytes
$size->toMegabytes(1);                          // Convert a quantity in this enum to megabytes
$size->toGigabytes(1);                          // Convert a quantity in this enum to gigabytes
$size->toTerabytes(1);                          // Convert a quantity in this enum to terabytes
$size->toPetabytes(1);                          // Convert a quantity in this enum to petabytes
$size->toExabytes(1, decimalDigits: 4);         // Convert a quantity in this enum to exabytes
$size->toBits(8);                               // Convert to bits (int, from ConvertsDataSizeUnits)
```

