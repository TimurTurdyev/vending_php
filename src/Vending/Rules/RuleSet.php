<?php
declare(strict_types=1);

namespace Vending\Rules;

use Vending\Catalog\Product;
use Vending\VendingException;

// программа аппарата: правила из настроек, оператор включает и выключает их
final class RuleSet
{
    /** @var Rule[] */
    private $rules = [];

    /** @var array[] настройки правил как в конфиге, вместе с enabled */
    private $definitions = [];

    public function __construct(array $definitions, RuleFactory $factory)
    {
        foreach ($definitions as $definition) {
            $rule = $factory->create($definition);
            $this->rules[$rule->id()] = $rule;
            $this->definitions[$rule->id()] = $definition + ['enabled' => true];
        }
    }

    /** @return Rule[] */
    public function all(): array
    {
        return $this->rules;
    }

    public function enabled(string $id): bool
    {
        return !empty($this->definitions[$id]['enabled']);
    }

    public function toggle(string $id, bool $enabled): void
    {
        if (!isset($this->rules[$id])) {
            throw VendingException::unknownRule($id);
        }

        $this->definitions[$id]['enabled'] = $enabled;
    }

    public function check(SaleContext $context): void
    {
        foreach ($this->active(Restriction::class) as $restriction) {
            $restriction->check($context);
        }
    }

    public function quote(Product $product, SaleContext $context): Quote
    {
        $price = $product->price();
        $labels = [];

        foreach ($this->active(PriceRule::class) as $rule) {
            $next = max(0, $rule->apply($price, $context));

            if ($next !== $price) {
                $labels[] = $rule->label();
                $price = $next;
            }
        }

        return new Quote($product->price(), $price, $labels);
    }

    public function toArray(): array
    {
        return array_values($this->definitions);
    }

    private function active(string $interface): array
    {
        return array_filter($this->rules, function (Rule $rule) use ($interface) {
            return $rule instanceof $interface && $this->enabled($rule->id());
        });
    }
}
