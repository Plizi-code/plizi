<?php

require_once __DIR__ . '/vendor/autoload.php';

class Preloader
{
    private array $ignores = [];

    private static int $count = 0;

    private array $paths;

    private array $fileMap;

    private array $classMap;

    public function __construct(string ...$paths)
    {
        $this->paths = $paths;
        $this->classMap = require __DIR__ . '/vendor/composer/autoload_classmap.php';
        $this->fileMap = array_flip($this->classMap);
    }

    public function paths(string ...$paths): Preloader
    {
        $this->paths = array_merge(
            $this->paths,
            $paths
        );

        return $this;
    }

    public function ignore(string ...$names): Preloader
    {
        $this->ignores = array_merge(
            $this->ignores,
            $names
        );

        return $this;
    }

    public function load(): void
    {
        foreach ($this->paths as $path) {
            $path = $this->classMap[$path] ?? $path;

            $this->loadPath(rtrim($path, '/'));
        }

        $count = self::$count;

        echo "[Preloader] Preloaded {$count} classes" . PHP_EOL;
    }

    private function loadPath(string $path): void
    {
        if (is_dir($path)) {
            $this->loadDir($path);

            return;
        }

        $this->loadFile($path);
    }

    private function loadDir(string $path): void
    {
        $handle = opendir($path);

        while ($file = readdir($handle)) {
            if (in_array($file, ['.', '..'])) {
                continue;
            }

            $this->loadPath("{$path}/{$file}");
        }

        closedir($handle);
    }

    private function loadFile(string $path): void
    {
        $class = $this->fileMap[$path] ?? null;

        if (! $class) {
            return;
        }

        if ($this->shouldIgnore($class)) {
            echo "[Preloader] Ignored `{$class}`" . PHP_EOL;

            return;
        }

        require_once($path);

        self::$count++;

        echo "[Preloader] Preloaded `{$class}`" . PHP_EOL;
    }

    private function shouldIgnore(?string $name): bool
    {
        if (! $name) {
            return true;
        }

        foreach ($this->ignores as $ignore) {
            if (strpos($name, $ignore) === 0) {
                return true;
            }
        }

        return false;
    }
}

(new Preloader())
    ->paths(
        __DIR__ . '/app',
        __DIR__ . '/vendor/laravel',
//        ...require(__DIR__ . '/preload_map.php')
    )
    ->ignore(
        \PHPUnit\Framework\TestCase::class,
        \Illuminate\Filesystem\Cache::class,
        \Illuminate\Log\LogManager::class,
        \Illuminate\Http\Testing\File::class,
        \Illuminate\Http\UploadedFile::class,
        \Illuminate\Support\Carbon::class,
        'Illuminate\Foundation\Testing',
        )
    ->load();
