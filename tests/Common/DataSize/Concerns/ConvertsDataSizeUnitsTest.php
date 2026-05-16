<?php

declare(strict_types=1);

use BensonDevs\SuperchargedEnums\Common\DataSize\BinaryDataSizeUnit;
use BensonDevs\SuperchargedEnums\Common\DataSize\DecimalDataSizeUnit;

test('DecimalDataSizeUnit toBits returns exact int', function () {
    expect(DecimalDataSizeUnit::Megabyte->toBits(2))->toBe(16_000_000);
});

test('DecimalDataSizeUnit toBytes converts with decimal precision', function () {
    expect(DecimalDataSizeUnit::Megabyte->toBytes(1))->toBe(1_000_000.0);
    expect(DecimalDataSizeUnit::Byte->toMegabytes(1, 0))->toBe(0.0);
});

test('DecimalDataSizeUnit toKilobytes honors decimalDigits', function () {
    expect(DecimalDataSizeUnit::Bit->toKilobytes(8000))->toBe(1.0);
    expect(DecimalDataSizeUnit::Bit->toKilobytes(8001, 4))->toBe(1.0001);
});

test('BinaryDataSizeUnit toBytes converts with decimal precision', function () {
    expect(BinaryDataSizeUnit::Mebibyte->toBytes(1))->toBe(1_048_576.0);
});

test('BinaryDataSizeUnit toKibibytes and toMebibytes convert correctly', function () {
    expect(BinaryDataSizeUnit::Bit->toKibibytes(8192))->toBe(1.0);
    expect(BinaryDataSizeUnit::Mebibyte->toKibibytes(1))->toBe(1024.0);
    expect(BinaryDataSizeUnit::Kibibyte->toMebibytes(1024))->toBe(1.0);
});
