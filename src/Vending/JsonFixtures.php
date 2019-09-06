<?php
declare(strict_types=1);

namespace Vending;

use RuntimeException;

final class JsonFixtures
{
    /** @var string */
    private $directory;

    public function __construct(string $directory)
    {
        $this->directory = rtrim($directory, '/');
    }

    public function wallet(): Coins
    {
        return new Coins($this->read('wallet'));
    }

    public function machine(): Coins
    {
        return new Coins($this->read('machine'));
    }

    /** @return Product[] */
    public function products(): array
    {
        return array_map([Product::class, 'fromArray'], $this->read('products'));
    }

    private function read(string $name): array
    {
        $file = $this->directory . '/' . $name . '.json';
        $data = is_file($file) ? json_decode((string)file_get_contents($file), true) : null;

        if (!is_array($data)) {
            throw new RuntimeException(sprintf('Cannot read "%s"', $file));
        }

        return $data;
    }
}
