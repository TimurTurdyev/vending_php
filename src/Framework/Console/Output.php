<?php
declare(strict_types=1);

namespace Framework\Console;

final class Output
{
    /** @var resource */
    private $stream;

    public function __construct($stream)
    {
        $this->stream = $stream;
    }

    public function write(string $text): void
    {
        fwrite($this->stream, $text);
    }

    public function line(string $text = ''): void
    {
        $this->write($text . PHP_EOL);
    }

    public function error(string $text): void
    {
        $this->line('Ошибка: ' . $text);
    }

    public function table(array $headers, array $rows): void
    {
        $widths = array_map('mb_strlen', $headers);

        foreach ($rows as $row) {
            foreach (array_values($row) as $i => $cell) {
                $widths[$i] = max($widths[$i], mb_strlen((string)$cell));
            }
        }

        $border = '+' . implode('+', array_map(function (int $width) {
            return str_repeat('-', $width + 2);
        }, $widths)) . '+';

        $this->line($border);
        $this->line($this->row($headers, $widths));
        $this->line($border);

        foreach ($rows as $row) {
            $this->line($this->row(array_values($row), $widths));
        }

        $this->line($border);
    }

    // str_pad считает байты, а не символы, поэтому кириллицу добиваем вручную
    private function row(array $cells, array $widths): string
    {
        $line = '|';

        foreach ($cells as $i => $cell) {
            $line .= ' ' . $cell . str_repeat(' ', $widths[$i] - mb_strlen((string)$cell)) . ' |';
        }

        return $line;
    }
}
