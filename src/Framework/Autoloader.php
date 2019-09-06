<?php
declare(strict_types=1);

namespace Framework;

// PSR-4: префикс пространства имен -> каталог с классами
final class Autoloader
{
    /** @var string[] */
    private $prefixes = [];

    public function addNamespace(string $prefix, string $directory): self
    {
        $this->prefixes[trim($prefix, '\\') . '\\'] = rtrim($directory, '/') . '/';

        return $this;
    }

    public function register(): void
    {
        spl_autoload_register([$this, 'load']);
    }

    public function load(string $class): void
    {
        foreach ($this->prefixes as $prefix => $directory) {
            if (strpos($class, $prefix) !== 0) {
                continue;
            }

            $file = $directory . str_replace('\\', '/', substr($class, strlen($prefix))) . '.php';

            if (is_file($file)) {
                require $file;

                return;
            }
        }
    }
}
