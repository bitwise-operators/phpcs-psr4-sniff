<?php

declare(strict_types=1);

namespace Suin\Sniffs\Classes\PSR4;

use Generator;
use InvalidArgumentException;
use RuntimeException;

use function dirname;
use function file_get_contents;
use function is_array;
use function is_file;
use function is_readable;
use function is_string;
use function json_decode;

final class AutoloadabilityInspectorsFactory
{
    public static function create(
        ?string $basePath,
        string $composerJsonPath,
    ): AutoloadabilityInspectors {
        $resolvedComposerJsonPath = $basePath === null
            ? $composerJsonPath
            : "{$basePath}/{$composerJsonPath}";

        if (!is_file($resolvedComposerJsonPath)) {
            throw new InvalidArgumentException("composer.json file not found: {$resolvedComposerJsonPath}");
        }

        if (!is_readable($resolvedComposerJsonPath)) {
            throw new InvalidArgumentException("composer.json file is not readable: {$resolvedComposerJsonPath}");
        }

        $psr4Directories = [];
        $inspectionRoot = dirname($resolvedComposerJsonPath);

        foreach (self::getNamespacesFromComposerJson($resolvedComposerJsonPath) as $namespace => $dir) {
            $psr4Directories[] = new AutoloadabilityInspector(
                "{$inspectionRoot}/{$dir}",
                $namespace,
            );
        }
        return new AutoloadabilityInspectors(...$psr4Directories);
    }

    /**
     * @return Generator<string, string>
     */
    private static function getNamespacesFromComposerJson(
        string $composerJsonPath,
    ): Generator {
        $contents = file_get_contents($composerJsonPath);

        if ($contents === false) {
            throw new RuntimeException("Unable to read file: {$composerJsonPath}");
        }
        $data = json_decode($contents, associative: true);

        if (!is_array($data)) {
            throw new RuntimeException("Unable to decode json: {$composerJsonPath}");
        }

        foreach (['autoload', 'autoload-dev'] as $autoload) {
            if (
                isset($data[$autoload])
                && is_array($data[$autoload])
                && isset($data[$autoload]['psr-4'])
            ) {
                if (!is_array($data[$autoload]['psr-4'])) {
                    throw new RuntimeException("Invalid composer.json; PSR4 element must be an array");
                }
                foreach ($data[$autoload]['psr-4'] as $namespace => $dirs) {
                    if (!is_string($namespace)) {
                        throw new RuntimeException("Invalid composer.json; PSR4 namespace must be a string");
                    }
                    foreach (is_array($dirs) ? $dirs : [$dirs] as $dir) {
                        if (!is_string($dir)) {
                            throw new RuntimeException("Invalid composer.json; PSR4 path must be a string");
                        }
                        yield $namespace => $dir;
                    }
                }
            }
        }
    }
}
