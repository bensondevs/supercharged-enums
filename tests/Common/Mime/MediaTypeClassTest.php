<?php

declare(strict_types=1);

use BensonDevs\SuperchargedEnums\Common\Mime\MediaTypeClass;

test('MediaTypeClass has eight cases and default Text', function () {
    expect(MediaTypeClass::cases())->toHaveCount(8);
    expect(MediaTypeClass::default())->toBe(MediaTypeClass::Text);
});

test('MediaTypeClass tryFrom and find resolve slugs', function () {
    expect(MediaTypeClass::tryFrom('application'))->toBe(MediaTypeClass::Application);
    expect(MediaTypeClass::tryFrom('multipart'))->toBe(MediaTypeClass::Multipart);
    expect(MediaTypeClass::tryFrom('model'))->toBeNull();
    expect(MediaTypeClass::find('image'))->toBe(MediaTypeClass::Image);
    expect(MediaTypeClass::find(null))->toBeNull();
});
