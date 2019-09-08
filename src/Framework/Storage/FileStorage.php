<?php
declare(strict_types=1);

namespace Framework\Storage;

use RuntimeException;

final class FileStorage implements Storage
{
    /** @var string */
    private $file;

    /** @var array|null */
    private $data;

    public function __construct(string $file)
    {
        $this->file = $file;
    }

    public function get(string $key)
    {
        return $this->data()[$key] ?? null;
    }

    public function set(string $key, $value): void
    {
        $data = $this->data();
        $data[$key] = $value;
        $this->save($data);
    }

    public function delete(string $key): void
    {
        $data = $this->data();
        unset($data[$key]);
        $this->save($data);
    }

    private function data(): array
    {
        if ($this->data === null) {
            $json = is_file($this->file) ? json_decode((string)file_get_contents($this->file), true) : null;
            $this->data = is_array($json) ? $json : [];
        }

        return $this->data;
    }

    private function save(array $data): void
    {
        $directory = dirname($this->file);

        if (!is_dir($directory) && !mkdir($directory, 0775, true) && !is_dir($directory)) {
            throw new RuntimeException(sprintf('Cannot create directory "%s"', $directory));
        }

        file_put_contents($this->file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), LOCK_EX);
        $this->data = $data;
    }
}
