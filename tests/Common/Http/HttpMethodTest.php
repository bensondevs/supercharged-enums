<?php

declare(strict_types=1);

use BensonDevs\SuperchargedEnums\Common\Http\HttpMethod;

test('HttpMethod has nine cases and default Get', function () {
    expect(HttpMethod::cases())->toHaveCount(9);
    expect(HttpMethod::default())->toBe(HttpMethod::Get);
});

test('HttpMethod tryFrom and find resolve slugs', function () {
    expect(HttpMethod::tryFrom('post'))->toBe(HttpMethod::Post);
    expect(HttpMethod::tryFrom('patch'))->toBe(HttpMethod::Patch);
    expect(HttpMethod::tryFrom('whistle'))->toBeNull();
    expect(HttpMethod::find('delete'))->toBe(HttpMethod::Delete);
    expect(HttpMethod::find(null))->toBeNull();
});
