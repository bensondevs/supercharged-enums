<?php

declare(strict_types=1);

use BensonDevs\SuperchargedEnums\Common\Text\TextCasing;

test('TextCasing has four cases and default Lower', function () {
    expect(TextCasing::cases())->toHaveCount(4);
    expect(TextCasing::default())->toBe(TextCasing::Lower);
});

test('TextCasing tryFrom and find resolve slugs', function () {
    expect(TextCasing::tryFrom('title'))->toBe(TextCasing::Title);
    expect(TextCasing::tryFrom('sentence'))->toBe(TextCasing::Sentence);
    expect(TextCasing::tryFrom('capital'))->toBeNull();
    expect(TextCasing::find('upper'))->toBe(TextCasing::Upper);
    expect(TextCasing::find(null))->toBeNull();
});
