# Common / Measure

Physical units with `toXxx()` conversions through a per-enum base unit. Temperature uses Celsius as the affine intermediate.

Namespace prefix: `BensonDevs\SuperchargedEnums\Common\Measure\`

## Enums

### `AreaUnit`

`BensonDevs\SuperchargedEnums\Common\Measure\AreaUnit` · backing: `string` · default: `SqMm` (`sq_mm`)

Common area units. Metric smallest-to-largest, then imperial smallest-to-largest. Conversions use square millimeters as the base unit. Backing values are lowercase English slugs.

| Case | Backing |
|------|--------|
| `SqMm` | `sq_mm` |
| `SqCm` | `sq_cm` |
| `SqM` | `sq_m` |
| `SqKm` | `sq_km` |
| `Hectare` | `hectare` |
| `Acre` | `acre` |
| `SqInch` | `sq_inch` |
| `SqFoot` | `sq_foot` |
| `SqYard` | `sq_yard` |
| `SqMile` | `sq_mile` |

**Enum-specific methods**

```php
use BensonDevs\SuperchargedEnums\Common\Measure\AreaUnit;

$quantity = AreaUnit::Acre;

$quantity->toSqMillimeters(1);                  // Convert a quantity in this enum to square millimeters
$quantity->toSqCentimeters(1);                  // Convert a quantity in this enum to square centimeters
$quantity->toSqMeters(2);                       // Convert a quantity in this enum to square meters
$quantity->toSqKilometers(1);                   // Convert a quantity in this enum to square kilometers
$quantity->toHectares(1);                       // Convert a quantity in this enum to hectares
$quantity->toAcres(1);                          // Convert a quantity in this enum to acres
$quantity->toSqInches(1);                       // Convert a quantity in this enum to square inches
$quantity->toSqFeet(1, decimalDigits: 4);       // Convert a quantity in this enum to square feet
$quantity->toSqYards(1);                        // Convert a quantity in this enum to square yards
$quantity->toSqMiles(1);                        // Convert a quantity in this enum to square miles
$quantity->toBaseUnits(1);                      // Convert to this enum's base unit (sq mm, from ConvertsMeasureUnits)
```

### `EnergyUnit`

`BensonDevs\SuperchargedEnums\Common\Measure\EnergyUnit` · backing: `string` · default: `Joule` (`joule`)

Common energy units. SI smallest-to-largest, then US customary. Conversions use joules as the base unit. `Calorie` is the thermochemical calorie. Backing values are lowercase English slugs.

| Case | Backing |
|------|--------|
| `Joule` | `joule` |
| `Kilojoule` | `kilojoule` |
| `Megajoule` | `megajoule` |
| `KilowattHour` | `kilowatt_hour` |
| `Calorie` | `calorie` |
| `Btu` | `btu` |

**Enum-specific methods**

```php
use BensonDevs\SuperchargedEnums\Common\Measure\EnergyUnit;

$quantity = EnergyUnit::KilowattHour;

$quantity->toJoules(1);                         // Convert a quantity in this enum to joules
$quantity->toKilojoules(1);                     // Convert a quantity in this enum to kilojoules
$quantity->toMegajoules(1);                     // Convert a quantity in this enum to megajoules
$quantity->toKilowattHours(1);                  // Convert a quantity in this enum to kilowatt-hours
$quantity->toCalories(1);                       // Convert a quantity in this enum to calories (thermochemical)
$quantity->toBtu(1);                            // Convert a quantity in this enum to BTU
$quantity->toBaseUnits(1);                      // Convert to this enum's base unit (joules, from ConvertsMeasureUnits)
```

### `FrequencyUnit`

`BensonDevs\SuperchargedEnums\Common\Measure\FrequencyUnit` · backing: `string` · default: `Hertz` (`hertz`)

Common frequency units. SI smallest-to-largest. Conversions use hertz as the base unit. Backing values are lowercase English slugs.

| Case | Backing |
|------|--------|
| `Hertz` | `hertz` |
| `Kilohertz` | `kilohertz` |
| `Megahertz` | `megahertz` |
| `Gigahertz` | `gigahertz` |

**Enum-specific methods**

```php
use BensonDevs\SuperchargedEnums\Common\Measure\FrequencyUnit;

$quantity = FrequencyUnit::Megahertz;

$quantity->toHertz(1);                          // Convert a quantity in this enum to hertz
$quantity->toKilohertz(1);                      // Convert a quantity in this enum to kilohertz
$quantity->toMegahertz(1);                      // Convert a quantity in this enum to megahertz
$quantity->toGigahertz(1);                      // Convert a quantity in this enum to gigahertz
$quantity->toBaseUnits(1);                      // Convert to this enum's base unit (hertz, from ConvertsMeasureUnits)
```

### `LengthUnit`

`BensonDevs\SuperchargedEnums\Common\Measure\LengthUnit` · backing: `string` · default: `Millimeter` (`mm`)

Common length units. Metric smallest-to-largest, then imperial smallest-to-largest. Inch, foot, yard, and mile use international conversion factors to millimeters. Backing values are lowercase slugs (metric uses short SI-style tokens mm, cm, m, km).

| Case | Backing |
|------|--------|
| `Millimeter` | `mm` |
| `Centimeter` | `cm` |
| `Meter` | `m` |
| `Kilometer` | `km` |
| `Inch` | `inch` |
| `Foot` | `foot` |
| `Yard` | `yard` |
| `Mile` | `mile` |

**Enum-specific methods**

```php
use BensonDevs\SuperchargedEnums\Common\Measure\LengthUnit;

$quantity = LengthUnit::Mile;

$quantity->toMillimeters(1);                    // Convert a quantity in this enum to millimeters
$quantity->toCentimeters(1);                    // Convert a quantity in this enum to centimeters
$quantity->toMeters(1);                         // Convert a quantity in this enum to meters
$quantity->toKilometers(1);                     // Convert a quantity in this enum to kilometers
$quantity->toInches(1);                         // Convert a quantity in this enum to inches
$quantity->toFeet(1);                           // Convert a quantity in this enum to feet
$quantity->toYards(1);                          // Convert a quantity in this enum to yards
$quantity->toMiles(1);                          // Convert a quantity in this enum to miles
$quantity->toBaseUnits(1);                      // Convert to this enum's base unit (millimeters, from ConvertsMeasureUnits)
```

### `MassUnit`

`BensonDevs\SuperchargedEnums\Common\Measure\MassUnit` · backing: `string` · default: `Milligram` (`milligram`)

Common mass units. Metric smallest-to-largest, then US customary. `Tonne` is the metric ton (1000 kg). Backing values are lowercase English slugs.

| Case | Backing |
|------|--------|
| `Milligram` | `milligram` |
| `Gram` | `gram` |
| `Kilogram` | `kilogram` |
| `Tonne` | `tonne` |
| `Ounce` | `ounce` |
| `Pound` | `pound` |

**Enum-specific methods**

```php
use BensonDevs\SuperchargedEnums\Common\Measure\MassUnit;

$quantity = MassUnit::Pound;

$quantity->toMilligrams(1);                     // Convert a quantity in this enum to milligrams
$quantity->toGrams(1);                          // Convert a quantity in this enum to grams
$quantity->toKilograms(1);                      // Convert a quantity in this enum to kilograms
$quantity->toTonnes(1);                         // Convert a quantity in this enum to tonnes (metric ton)
$quantity->toOunces(1);                         // Convert a quantity in this enum to ounces
$quantity->toPounds(1);                         // Convert a quantity in this enum to pounds
$quantity->toBaseUnits(1);                      // Convert to this enum's base unit (milligrams, from ConvertsMeasureUnits)
```

### `PowerUnit`

`BensonDevs\SuperchargedEnums\Common\Measure\PowerUnit` · backing: `string` · default: `Watt` (`watt`)

Common power units. SI smallest-to-largest, then US mechanical horsepower. Conversions use watts as the base unit. Backing values are lowercase English slugs.

| Case | Backing |
|------|--------|
| `Watt` | `watt` |
| `Kilowatt` | `kilowatt` |
| `Megawatt` | `megawatt` |
| `Horsepower` | `horsepower` |

**Enum-specific methods**

```php
use BensonDevs\SuperchargedEnums\Common\Measure\PowerUnit;

$quantity = PowerUnit::Horsepower;

$quantity->toWatts(1);                          // Convert a quantity in this enum to watts
$quantity->toKilowatts(1);                      // Convert a quantity in this enum to kilowatts
$quantity->toMegawatts(1);                      // Convert a quantity in this enum to megawatts
$quantity->toHorsepower(1);                     // Convert a quantity in this enum to mechanical horsepower
$quantity->toBaseUnits(1);                      // Convert to this enum's base unit (watts, from ConvertsMeasureUnits)
```

### `PressureUnit`

`BensonDevs\SuperchargedEnums\Common\Measure\PressureUnit` · backing: `string` · default: `Pascal` (`pascal`)

Common pressure units. SI smallest-to-largest, then US customary. Conversions use pascals as the base unit. Backing values are lowercase English slugs.

| Case | Backing |
|------|--------|
| `Pascal` | `pascal` |
| `Kilopascal` | `kilopascal` |
| `Bar` | `bar` |
| `Psi` | `psi` |
| `Atmosphere` | `atmosphere` |

**Enum-specific methods**

```php
use BensonDevs\SuperchargedEnums\Common\Measure\PressureUnit;

$quantity = PressureUnit::Psi;

$quantity->toPascals(1);                        // Convert a quantity in this enum to pascals
$quantity->toKilopascals(1);                    // Convert a quantity in this enum to kilopascals
$quantity->toBars(1);                           // Convert a quantity in this enum to bars
$quantity->toPsi(1);                            // Convert a quantity in this enum to psi
$quantity->toAtmospheres(1);                    // Convert a quantity in this enum to atmospheres
$quantity->toBaseUnits(1);                      // Convert to this enum's base unit (pascals, from ConvertsMeasureUnits)
```

### `SpeedUnit`

`BensonDevs\SuperchargedEnums\Common\Measure\SpeedUnit` · backing: `string` · default: `MPerS` (`m_per_s`)

Common speed units. Metric first, then US customary and nautical. Conversions use meters per second as the base unit. Backing values are lowercase English slugs.

| Case | Backing |
|------|--------|
| `MPerS` | `m_per_s` |
| `KmPerH` | `km_per_h` |
| `Mph` | `mph` |
| `Knot` | `knot` |

**Enum-specific methods**

```php
use BensonDevs\SuperchargedEnums\Common\Measure\SpeedUnit;

$quantity = SpeedUnit::Mph;

$quantity->toMetersPerSecond(1);                // Convert a quantity in this enum to meters per second
$quantity->toKilometersPerHour(1);              // Convert a quantity in this enum to kilometers per hour
$quantity->toMilesPerHour(1);                   // Convert a quantity in this enum to miles per hour
$quantity->toKnots(1);                          // Convert a quantity in this enum to knots
$quantity->toBaseUnits(1);                      // Convert to this enum's base unit (m/s, from ConvertsMeasureUnits)
```

### `TemperatureUnit`

`BensonDevs\SuperchargedEnums\Common\Measure\TemperatureUnit` · backing: `string` · default: `Celsius` (`celsius`)

Common temperature scales. Conversions use Celsius as the canonical intermediate (affine transforms, not multiplicative). Backing values are lowercase English slugs.

| Case | Backing |
|------|--------|
| `Celsius` | `celsius` |
| `Fahrenheit` | `fahrenheit` |
| `Kelvin` | `kelvin` |

**Enum-specific methods**

```php
use BensonDevs\SuperchargedEnums\Common\Measure\TemperatureUnit;

$reading = TemperatureUnit::Fahrenheit;

$reading->toCelsius(32.0);                      // Convert a quantity in this enum to Celsius (affine, not multiplicative)
$reading->toFahrenheit(100.0);                  // Convert a quantity in this enum to Fahrenheit
$reading->toKelvin(0.0, decimalDigits: 4);      // Convert a quantity in this enum to Kelvin
```

### `VolumeUnit`

`BensonDevs\SuperchargedEnums\Common\Measure\VolumeUnit` · backing: `string` · default: `Milliliter` (`milliliter`)

Common volume units. Metric first, then **US liquid** customary units only. Backing values are lowercase English slugs.

| Case | Backing |
|------|--------|
| `Milliliter` | `milliliter` |
| `Liter` | `liter` |
| `UsLiquidGallon` | `us_liquid_gallon` |
| `UsLiquidQuart` | `us_liquid_quart` |
| `UsLiquidPint` | `us_liquid_pint` |
| `UsCup` | `us_cup` |
| `UsFluidOunce` | `us_fluid_ounce` |

**Enum-specific methods**

```php
use BensonDevs\SuperchargedEnums\Common\Measure\VolumeUnit;

$quantity = VolumeUnit::UsLiquidGallon;

$quantity->toMilliliters(1);                    // Convert a quantity in this enum to milliliters
$quantity->toLiters(1);                         // Convert a quantity in this enum to liters
$quantity->toUsLiquidGallons(1);                // Convert a quantity in this enum to US liquid gallons
$quantity->toUsLiquidQuarts(1);                 // Convert a quantity in this enum to US liquid quarts
$quantity->toUsLiquidPints(1);                  // Convert a quantity in this enum to US liquid pints
$quantity->toUsCups(1);                         // Convert a quantity in this enum to US cups
$quantity->toUsFluidOunces(1);                  // Convert a quantity in this enum to US fluid ounces
$quantity->toBaseUnits(1);                      // Convert to this enum's base unit (milliliters, from ConvertsMeasureUnits)
```

