<?php
declare(strict_types=1);

namespace Framework\Storage;

use RuntimeException;

// JSON-файл, который читают и меняют несколько процессов сразу: веб и консоль
final class JsonFile
{
    /** @var string */
    private $path;

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function read(): ?array
    {
        if (!is_file($this->path)) {
            return null;
        }

        $handle = $this->open('rb', LOCK_SH);

        try {
            return $this->decode((string)stream_get_contents($handle));
        } finally {
            $this->close($handle);
        }
    }

    // $change получает данные по ссылке; файл перезаписывается, только если $change не бросил исключение
    public function update(callable $change)
    {
        $directory = dirname($this->path);

        if (!is_dir($directory) && !mkdir($directory, 0775, true) && !is_dir($directory)) {
            throw new RuntimeException(sprintf('Cannot create directory "%s"', $directory));
        }

        $handle = $this->open('c+b', LOCK_EX);

        try {
            $data = $this->decode((string)stream_get_contents($handle));
            $result = $change($data);

            ftruncate($handle, 0);
            rewind($handle);
            fwrite($handle, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            fflush($handle);

            return $result;
        } finally {
            $this->close($handle);
        }
    }

    public function delete(): void
    {
        if (is_file($this->path)) {
            unlink($this->path);
        }
    }

    private function open(string $mode, int $lock)
    {
        $handle = fopen($this->path, $mode);

        if ($handle === false || !flock($handle, $lock)) {
            throw new RuntimeException(sprintf('Cannot lock "%s"', $this->path));
        }

        return $handle;
    }

    private function close($handle): void
    {
        flock($handle, LOCK_UN);
        fclose($handle);
    }

    private function decode(string $json): ?array
    {
        $data = json_decode($json, true);

        return is_array($data) ? $data : null;
    }
}
