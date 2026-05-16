<?php

declare(strict_types=1);

use BensonDevs\SuperchargedEnums\Common\Identity\IdentityDocumentType;

test('IdentityDocumentType has six cases and default Passport', function () {
    expect(IdentityDocumentType::cases())->toHaveCount(6);
    expect(IdentityDocumentType::default())->toBe(IdentityDocumentType::Passport);
});

test('IdentityDocumentType tryFrom and find resolve slugs', function () {
    expect(IdentityDocumentType::tryFrom('drivers_license'))->toBe(IdentityDocumentType::DriversLicense);
    expect(IdentityDocumentType::tryFrom('national_id'))->toBe(IdentityDocumentType::NationalId);
    expect(IdentityDocumentType::tryFrom('unknown'))->toBeNull();
    expect(IdentityDocumentType::find('visa'))->toBe(IdentityDocumentType::Visa);
    expect(IdentityDocumentType::find(null))->toBeNull();
});
