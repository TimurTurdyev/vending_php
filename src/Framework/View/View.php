<?php
declare(strict_types=1);

namespace Framework\View;

use RuntimeException;
use Throwable;

final class View
{
    /** @var string */
    private $directory;

    public function __construct(string $directory)
    {
        $this->directory = rtrim($directory, '/');
    }

    public function render(string $template, array $data = []): string
    {
        $file = $this->directory . '/' . $template;

        if (!is_file($file)) {
            throw new RuntimeException(sprintf('Template "%s" not found', $file));
        }

        extract($data, EXTR_SKIP);
        ob_start();

        try {
            require $file;
        } catch (Throwable $e) {
            ob_end_clean();
            throw $e;
        }

        return (string)ob_get_clean();
    }

    public function e($value): string
    {
        return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
    }
}
