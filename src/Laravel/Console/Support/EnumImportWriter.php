<?php

declare(strict_types=1);

namespace BensonDevs\SuperchargedEnums\Laravel\Console\Support;

use Illuminate\Support\Facades\File;
use RuntimeException;

final class EnumImportWriter
{
    public function __construct(
        private readonly EnumFileRenderer $fileRenderer,
    ) {}

    /**
     * @param  list<BuiltEnumCase>  $cases
     * @param  array{
     *     force?: bool,
     *     interactive?: bool,
     *     confirm?: ?callable(string): bool,
     * }  $overwrite
     * @return array{written: bool, path: string, contents: string}
     */
    public function write(
        string $path,
        string $namespace,
        string $class,
        string $backingType,
        array $cases,
        array $overwrite = [],
    ): array {
        $force = $overwrite['force'] ?? false;
        $interactive = $overwrite['interactive'] ?? false;
        $confirm = $overwrite['confirm'] ?? null;

        if (File::exists($path) && ! $force) {
            if ($interactive && is_callable($confirm)) {
                $message = "Enum [{$class}] already exists at [{$path}]. Replace it?";

                if (! $confirm($message)) {
                    return [
                        'written' => false,
                        'path' => $path,
                        'contents' => '',
                    ];
                }
            } else {
                throw new RuntimeException("Enum file already exists at [{$path}]. Use --force to overwrite.");
            }
        }

        $contents = $this->fileRenderer->render($namespace, $class, $backingType, $cases);

        File::ensureDirectoryExists(dirname($path));
        File::put($path, $contents);

        return [
            'written' => true,
            'path' => $path,
            'contents' => $contents,
        ];
    }
}
