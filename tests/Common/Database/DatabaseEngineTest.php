<?php

declare(strict_types=1);

use BensonDevs\SuperchargedEnums\Common\Database\DatabaseEngine;

test('DatabaseEngine has five cases and default Mysql', function () {
    expect(DatabaseEngine::cases())->toHaveCount(5);
    expect(DatabaseEngine::default())->toBe(DatabaseEngine::Mysql);
});

test('DatabaseEngine tryFrom and find resolve slugs', function () {
    expect(DatabaseEngine::tryFrom('pgsql'))->toBe(DatabaseEngine::Pgsql);
    expect(DatabaseEngine::tryFrom('sqlserver'))->toBe(DatabaseEngine::Sqlserver);
    expect(DatabaseEngine::tryFrom('oracle'))->toBeNull();
    expect(DatabaseEngine::find('sqlite'))->toBe(DatabaseEngine::Sqlite);
    expect(DatabaseEngine::find(null))->toBeNull();
});
