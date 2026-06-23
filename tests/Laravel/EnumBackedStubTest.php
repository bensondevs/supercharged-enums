<?php

declare(strict_types=1);

it('ships a backed enum stub for Laravel make:enum', function (): void {
    $stubFile = __DIR__.'/../../stubs/enum.backed.stub';

    expect(file_exists($stubFile))->toBeTrue();

    $content = file_get_contents($stubFile);
    expect($content)->not->toBeFalse()->not->toBe('')
        ->toContain('EnumExtension')
        ->toContain('use EnumExtension')
        ->toContain('{{ namespace }}')
        ->toContain('{{ class }}')
        ->toContain('{{ type }}');
});
