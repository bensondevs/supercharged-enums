<?php

declare(strict_types=1);

use BensonDevs\SuperchargedEnums\Common\Http\HttpStatusCode;

test('HttpStatusCode has 62 IANA cases and default Continue', function () {
    expect(HttpStatusCode::cases())->toHaveCount(62);
    expect(HttpStatusCode::default())->toBe(HttpStatusCode::Continue);
});

test('HttpStatusCode tryFrom and find resolve status numbers', function () {
    expect(HttpStatusCode::tryFrom(404))->toBe(HttpStatusCode::NotFound);
    expect(HttpStatusCode::tryFrom(503))->toBe(HttpStatusCode::ServiceUnavailable);
    expect(HttpStatusCode::tryFrom(999))->toBeNull();
    expect(HttpStatusCode::find('404'))->toBe(HttpStatusCode::NotFound);
    expect(HttpStatusCode::find(null))->toBeNull();
});

test('HttpStatusCode covers each status series', function () {
    expect(HttpStatusCode::NoContent->value)->toBe(204);
    expect(HttpStatusCode::Found->value)->toBe(302);
    expect(HttpStatusCode::UnavailableForLegalReasons->value)->toBe(451);
    expect(HttpStatusCode::ServiceUnavailable->value)->toBe(503);
});

test('HttpStatusCode classifies RFC status series', function () {
    expect(HttpStatusCode::Continue->isInformational())->toBeTrue();
    expect(HttpStatusCode::Ok->isSuccess())->toBeTrue();
    expect(HttpStatusCode::NoContent->isSuccess())->toBeTrue();
    expect(HttpStatusCode::Found->isRedirect())->toBeTrue();
    expect(HttpStatusCode::NotModified->isRedirect())->toBeTrue();
    expect(HttpStatusCode::NotFound->isClientError())->toBeTrue();
    expect(HttpStatusCode::TooManyRequests->isClientError())->toBeTrue();
    expect(HttpStatusCode::ServiceUnavailable->isServerError())->toBeTrue();

    expect(HttpStatusCode::NotFound->series())->toBe(4);
    expect(HttpStatusCode::NotFound->isError())->toBeTrue();
    expect(HttpStatusCode::Found->isSuccess())->toBeFalse();
    expect(HttpStatusCode::Continue->isError())->toBeFalse();

    foreach (HttpStatusCode::cases() as $case) {
        $classCount = (int) $case->isInformational()
            + (int) $case->isSuccess()
            + (int) $case->isRedirect()
            + (int) $case->isClientError()
            + (int) $case->isServerError();

        expect($classCount)->toBe(1);
        expect($case->isError())->toBe($case->series() === 4 || $case->series() === 5);
    }
});
