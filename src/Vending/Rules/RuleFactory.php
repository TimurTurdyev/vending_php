<?php
declare(strict_types=1);

namespace Vending\Rules;

use InvalidArgumentException;

final class RuleFactory
{
    private const TYPES = [
        'happy_hours' => HappyHours::class,
        'every_nth_free' => EveryNthFree::class,
        'night_markup' => NightMarkup::class,
        'working_hours' => WorkingHours::class,
    ];

    public function create(array $definition): Rule
    {
        $type = $definition['type'] ?? '';

        if (!isset(self::TYPES[$type])) {
            throw new InvalidArgumentException(sprintf('Неизвестный тип правила "%s"', $type));
        }

        return [self::TYPES[$type], 'fromArray']($definition);
    }
}
