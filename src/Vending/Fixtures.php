<?php
declare(strict_types=1);

namespace Vending;

use RuntimeException;

// начальные данные из data/: аппараты и покупатели
final class Fixtures
{
    /** @var string */
    private $directory;

    public function __construct(string $directory)
    {
        $this->directory = rtrim($directory, '/');
    }

    /** @return array[] id => настройки аппарата */
    public function machines(): array
    {
        $machines = [];

        foreach (glob($this->directory . '/machines/*.json') as $file) {
            $machine = $this->read($file);
            $machines[$machine['id']] = $machine;
        }

        return $machines;
    }

    public function customers(): array
    {
        return $this->read($this->directory . '/customers.json');
    }

    private function read(string $file): array
    {
        $data = is_file($file) ? json_decode((string)file_get_contents($file), true) : null;

        if (!is_array($data)) {
            throw new RuntimeException(sprintf('Cannot read "%s"', $file));
        }

        return $data;
    }
}
