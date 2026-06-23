<?php

declare(strict_types=1);

/**
 * @param  array<string, mixed>  $frontmatter
 */
function parseBoostSkillFrontmatter(string $content): array
{
    if (! preg_match('/^\s*---\s*\n(.*?)\n---\s*\n/s', $content, $matches)) {
        return [];
    }

    $lines = explode("\n", $matches[1]);
    $frontmatter = [];
    $currentKey = null;

    foreach ($lines as $line) {
        if (preg_match('/^([a-zA-Z0-9_-]+):\s*(.*)$/', $line, $parts)) {
            $currentKey = $parts[1];
            $value = trim($parts[2], " \t\"'");
            $frontmatter[$currentKey] = $value;

            continue;
        }

        if ($currentKey !== null && preg_match('/^\s+(.+)$/', $line, $parts)) {
            $frontmatter[$currentKey] = trim($parts[1], " \t\"'");
        }
    }

    return $frontmatter;
}

it('ships valid Laravel Boost skills', function (): void {
    $skillFiles = glob(__DIR__.'/../../resources/boost/skills/*/SKILL.md') ?: [];

    expect($skillFiles)->not->toBeEmpty();

    foreach ($skillFiles as $skillFile) {
        $content = file_get_contents($skillFile);
        expect($content)->not->toBeFalse()->not->toBe('');

        $frontmatter = parseBoostSkillFrontmatter($content);

        expect($frontmatter)->toHaveKeys(['name', 'description'])
            ->and($frontmatter['name'])->not->toBe('')
            ->and($frontmatter['description'])->not->toBe('');

        $directoryName = basename(dirname($skillFile));
        expect($frontmatter['name'])->toBe($directoryName);
    }
});

it('ships Laravel Boost guidelines', function (): void {
    $guidelineFile = __DIR__.'/../../resources/boost/guidelines/core.blade.php';

    expect(file_exists($guidelineFile))->toBeTrue();

    $content = file_get_contents($guidelineFile);
    expect($content)->not->toBeFalse()->not->toBe('');
});
