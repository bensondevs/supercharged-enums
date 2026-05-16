<?php

declare(strict_types=1);

use BensonDevs\SuperchargedEnums\Common\Finance\CardBrand;

test('CardBrand has nine cases and default Visa', function () {
    expect(CardBrand::cases())->toHaveCount(9);
    expect(CardBrand::default())->toBe(CardBrand::Visa);
});

test('CardBrand tryFrom and find resolve slugs', function () {
    expect(CardBrand::tryFrom('mastercard'))->toBe(CardBrand::Mastercard);
    expect(CardBrand::tryFrom('cartes_bancaires'))->toBe(CardBrand::CartesBancaires);
    expect(CardBrand::tryFrom('american_express'))->toBeNull();
    expect(CardBrand::find('unionpay'))->toBe(CardBrand::Unionpay);
    expect(CardBrand::find(null))->toBeNull();
});
