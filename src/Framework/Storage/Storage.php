<?php
declare(strict_types=1);

namespace Framework\Storage;

interface Storage
{
    public function get(string $key);

    public function set(string $key, $value): void;

    public function delete(string $key): void;
}
