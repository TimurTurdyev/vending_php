<?php
declare(strict_types=1);

namespace Vending;

use DateTimeImmutable;
use Vending\Catalog\Product;
use Vending\Catalog\Slot;
use Vending\Money\CashBox;
use Vending\Money\Coins;
use Vending\Money\Currency;
use Vending\Rules\Quote;
use Vending\Rules\RuleSet;
use Vending\Rules\SaleContext;
use Vending\Sales\Journal;
use Vending\Sales\Sale;

final class Machine
{
    /** @var MachineInfo */
    private $info;

    /** @var Product[] артикул => товар */
    private $products = [];

    /** @var Slot[] код => ячейка */
    private $slots = [];

    /** @var CashBox */
    private $cash;

    /** @var RuleSet */
    private $rules;

    /** @var Journal */
    private $journal;

    /** @var Occupancy|null */
    private $occupancy;

    public function __construct(
        MachineInfo $info,
        array $products,
        array $slots,
        CashBox $cash,
        RuleSet $rules,
        Journal $journal,
        ?Occupancy $occupancy = null
    ) {
        $this->info = $info;

        foreach ($products as $product) {
            $this->products[$product->sku()] = $product;
        }

        foreach ($slots as $slot) {
            $this->slots[$slot->code()] = $slot;
        }

        $this->cash = $cash;
        $this->rules = $rules;
        $this->journal = $journal;
        $this->occupancy = $occupancy;
    }

    public function id(): string
    {
        return $this->info->id();
    }

    public function info(): MachineInfo
    {
        return $this->info;
    }

    public function currency(): Currency
    {
        return $this->cash->currency();
    }

    public function cash(): CashBox
    {
        return $this->cash;
    }

    public function rules(): RuleSet
    {
        return $this->rules;
    }

    public function journal(): Journal
    {
        return $this->journal;
    }

    public function occupancy(): ?Occupancy
    {
        return $this->occupancy;
    }

    /** @return Product[] */
    public function products(): array
    {
        return $this->products;
    }

    public function product(string $sku): Product
    {
        if (!isset($this->products[$sku])) {
            throw VendingException::unknownProduct($sku);
        }

        return $this->products[$sku];
    }

    /** @return Slot[] */
    public function slots(): array
    {
        return $this->slots;
    }

    public function slot(string $code): Slot
    {
        $code = strtoupper($code);

        if (!isset($this->slots[$code])) {
            throw VendingException::unknownSlot($code);
        }

        return $this->slots[$code];
    }

    public function quote(Slot $slot, Customer $customer, DateTimeImmutable $now): ?Quote
    {
        if ($slot->sku() === null) {
            return null;
        }

        $product = $this->product($slot->sku());

        return $this->rules->quote($product, $this->context($product, $customer, $now))
            ->roundedTo($this->cash->smallestCoin());
    }

    public function busyFor(Customer $customer, DateTimeImmutable $now): bool
    {
        return $this->occupancy !== null
            && $this->occupancy->customer() !== $customer->id()
            && $this->occupancy->secondsLeft($now, $this->info->timeout()) > 0;
    }

    public function insert(Customer $customer, int $coin, DateTimeImmutable $now): void
    {
        $this->assertFree($customer, $now);

        if (!$this->cash->accepts($coin)) {
            throw VendingException::unknownCoin($this->currency()->format($coin));
        }

        $customer->spend($this->currency(), $coin);
        $this->cash->insert($coin);
        $this->occupancy = new Occupancy($customer->id(), $now->getTimestamp());
    }

    public function buy(Customer $customer, string $code, DateTimeImmutable $now, bool $ageConfirmed = false): Purchase
    {
        $this->assertFree($customer, $now);

        $slot = $this->slot($code);

        if (!$slot->inStock()) {
            throw VendingException::emptySlot($slot->code());
        }

        $product = $this->product($slot->sku());
        $context = $this->context($product, $customer, $now, $ageConfirmed);
        $this->rules->check($context);

        $quote = $this->rules->quote($product, $context)->roundedTo($this->cash->smallestCoin());
        $change = $this->cash->pay($quote->price());
        $slot->take();

        $this->journal->record(new Sale($now, $customer->id(), $product->sku(), $product->name(), $quote->price()));
        $customer->receive($this->currency(), $change);
        $this->occupancy = null;

        return new Purchase($product, $quote, $change);
    }

    public function refund(Customer $customer, DateTimeImmutable $now): Purchase
    {
        $this->assertFree($customer, $now);

        return $this->giveBack($customer);
    }

    // покупатель отошел и не вернулся: внесенное возвращается ему же
    public function abandonedBy(DateTimeImmutable $now): ?string
    {
        if ($this->occupancy === null || $this->occupancy->secondsLeft($now, $this->info->timeout()) > 0) {
            return null;
        }

        return $this->occupancy->customer();
    }

    public function giveBack(Customer $customer): Purchase
    {
        $change = $this->cash->refund();
        $customer->receive($this->currency(), $change);
        $this->occupancy = null;

        return new Purchase(null, null, $change);
    }

    public function restock(string $code, ?int $quantity = null): int
    {
        return $this->slot($code)->restock($quantity);
    }

    public function load(string $code, string $sku, int $quantity): void
    {
        $this->slot($code)->load($this->product($sku)->sku(), $quantity);
    }

    public function setPrice(string $sku, int $price): void
    {
        $coin = $this->cash->smallestCoin();

        if ($price <= 0 || $price % $coin !== 0) {
            throw VendingException::unpayablePrice($this->currency()->format($coin));
        }

        $this->products[$sku] = $this->product($sku)->withPrice($price);
    }

    public function collect(): Coins
    {
        return $this->cash->collect();
    }

    private function assertFree(Customer $customer, DateTimeImmutable $now): void
    {
        if ($this->busyFor($customer, $now)) {
            throw VendingException::busy();
        }
    }

    private function context(Product $product, Customer $customer, DateTimeImmutable $now, bool $ageConfirmed = false): SaleContext
    {
        return new SaleContext(
            $product,
            $customer->id(),
            $now,
            $this->journal->soldCount($product->sku()),
            $this->journal->customerCount($customer->id(), $now),
            $ageConfirmed
        );
    }
}
