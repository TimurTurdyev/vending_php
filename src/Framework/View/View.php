<?php
declare(strict_types=1);

namespace Framework\View;

use RuntimeException;
use Throwable;

final class View
{
    /** @var string */
    private $directory;

    /** @var array[] макеты шаблонов, которые сейчас рендерятся: [файл, данные] или null */
    private $layouts = [];

    public function __construct(string $directory)
    {
        $this->directory = rtrim($directory, '/');
    }

    public function render(string $template, array $data = []): string
    {
        $content = $this->capture($template, $data, $layout);

        while ($layout !== null) {
            [$file, $extra] = $layout;
            $content = $this->capture($file, ['content' => $content] + $extra + $data, $layout);
        }

        return $content;
    }

    // вызывается из шаблона: обернуть результат в макет и передать ему данные
    public function extend(string $layout, array $data = []): void
    {
        $this->layouts[count($this->layouts) - 1] = [$layout, $data];
    }

    public function e($value): string
    {
        return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
    }

    private function capture(string $template, array $data, &$layout): string
    {
        $file = $this->directory . '/' . $template;

        if (!is_file($file)) {
            throw new RuntimeException(sprintf('Template "%s" not found', $file));
        }

        $this->layouts[] = null;
        extract($data, EXTR_SKIP);
        ob_start();

        try {
            require $file;
        } catch (Throwable $e) {
            ob_end_clean();
            array_pop($this->layouts);
            throw $e;
        }

        $layout = array_pop($this->layouts);

        return (string)ob_get_clean();
    }
}
