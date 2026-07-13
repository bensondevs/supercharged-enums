<?php

declare(strict_types=1);

namespace BensonDevs\SuperchargedEnums\Laravel\Console\Support;

use Illuminate\Support\Str;

final class EnumImporterNameResolver
{
    public function resolveEnumClassName(string $importerClassName): string
    {
        $baseName = class_basename($importerClassName);

        if (str_ends_with($baseName, 'EnumImporter')) {
            return Str::studly(substr($baseName, 0, -strlen('EnumImporter')) . 'Enum');
        }

        if (str_ends_with($baseName, 'Importer')) {
            $name = Str::studly(substr($baseName, 0, -strlen('Importer')));

            return str_ends_with($name, 'Enum') ? $name : $name . 'Enum';
        }

        return Str::studly($baseName);
    }

    public function resolveImporterClass(string $name, string $namespace): string
    {
        $class = Str::studly($name);

        if (str_contains($class, '\\')) {
            return ltrim($class, '\\');
        }

        return trim($namespace, '\\') . '\\' . $class;
    }

    public function namespaceFromPath(string $path): string
    {
        $segments = array_values(array_filter(explode('/', trim(str_replace('\\', '/', $path), '/'))));

        if ($segments !== [] && $segments[0] === 'app') {
            $segments[0] = 'App';
        }

        return implode('\\', array_map(static fn (string $segment): string => Str::studly($segment), $segments));
    }

    public function pathFromNamespace(string $namespace, string $class): string
    {
        $segments = explode('\\', $namespace);
        $pathSegments = array_map(static fn (string $segment): string => Str::studly($segment), $segments);

        if ($pathSegments[0] === 'App') {
            $pathSegments[0] = 'app';
        }

        return base_path(strtolower(implode('/', $pathSegments)) . '/' . $class . '.php');
    }
}
