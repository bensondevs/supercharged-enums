<?php

declare(strict_types=1);

use BensonDevs\SuperchargedEnums\Common\Calendar\DateDisplayFormat;

test('DateDisplayFormat has nine cases and default IsoDisplay', function () {
    expect(DateDisplayFormat::cases())->toHaveCount(9);
    expect(DateDisplayFormat::default())->toBe(DateDisplayFormat::IsoDisplay);
});

test('DateDisplayFormat tryFrom and find resolve slugs', function () {
    expect(DateDisplayFormat::tryFrom('iso_display'))->toBe(DateDisplayFormat::IsoDisplay);
    expect(DateDisplayFormat::tryFrom('us_numeric'))->toBe(DateDisplayFormat::UsNumeric);
    expect(DateDisplayFormat::tryFrom('invalid'))->toBeNull();
    expect(DateDisplayFormat::find('european_numeric'))->toBe(DateDisplayFormat::EuropeanNumeric);
    expect(DateDisplayFormat::find(null))->toBeNull();
});

test('DateDisplayFormat format returns PHP date patterns', function (DateDisplayFormat $case, string $pattern) {
    expect($case->format())->toBe($pattern);
})->with([
    [DateDisplayFormat::IsoDisplay, 'Y-m-d'],
    [DateDisplayFormat::UsNumeric, 'm/d/Y'],
    [DateDisplayFormat::UsLong, 'F j, Y'],
    [DateDisplayFormat::EuropeanNumeric, 'd/m/Y'],
    [DateDisplayFormat::EuropeanDots, 'd.m.Y'],
    [DateDisplayFormat::BritishNumeric, 'd/m/Y'],
    [DateDisplayFormat::ShortMonth, 'd-M-Y'],
    [DateDisplayFormat::LongMonth, 'j F Y'],
    [DateDisplayFormat::Japanese, 'Y年n月j日'],
]);

test('DateDisplayFormat renders fixed date without day-month ambiguity', function () {
    $date = new DateTimeImmutable('2026-05-16');

    expect($date->format(DateDisplayFormat::IsoDisplay->format()))->toBe('2026-05-16');
    expect($date->format(DateDisplayFormat::UsNumeric->format()))->toBe('05/16/2026');
    expect($date->format(DateDisplayFormat::EuropeanNumeric->format()))->toBe('16/05/2026');
});
