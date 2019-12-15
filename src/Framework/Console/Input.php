<?php
declare(strict_types=1);

namespace Framework\Console;

// buy A1 --as=ivan --confirm-age -> аргументы [buy, A1], опции [as => ivan, confirm-age => true]
final class Input
{
    /** @var string[] */
    private $arguments = [];

    /** @var array */
    private $options = [];

    public function __construct(array $tokens)
    {
        foreach ($tokens as $token) {
            if (strpos($token, '--') === 0) {
                $parts = explode('=', substr($token, 2), 2);
                $this->options[$parts[0]] = $parts[1] ?? true;
            } else {
                $this->arguments[] = $token;
            }
        }
    }

    public function arguments(): array
    {
        return $this->arguments;
    }

    public function argument(int $index, ?string $default = null): ?string
    {
        return $this->arguments[$index] ?? $default;
    }

    public function option(string $name, $default = null)
    {
        return $this->options[$name] ?? $default;
    }

    public function options(): array
    {
        return $this->options;
    }

    // без первого аргумента: имя команды уже разобрано
    public function shift(): self
    {
        $input = clone $this;
        array_shift($input->arguments);

        return $input;
    }
}
