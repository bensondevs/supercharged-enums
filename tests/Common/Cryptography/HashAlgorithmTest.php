<?php

declare(strict_types=1);

use BensonDevs\SuperchargedEnums\Common\Cryptography\HashAlgorithm;

test('HashAlgorithm has eight cases and default Md5', function () {
    expect(HashAlgorithm::cases())->toHaveCount(8);
    expect(HashAlgorithm::default())->toBe(HashAlgorithm::Md5);
});

test('HashAlgorithm tryFrom and find resolve slugs', function () {
    expect(HashAlgorithm::tryFrom('sha256'))->toBe(HashAlgorithm::Sha256);
    expect(HashAlgorithm::tryFrom('sha3_512'))->toBe(HashAlgorithm::Sha3_512);
    expect(HashAlgorithm::tryFrom('ripemd160'))->toBeNull();
    expect(HashAlgorithm::find('blake2b'))->toBe(HashAlgorithm::Blake2b);
    expect(HashAlgorithm::find(null))->toBeNull();
});
