<?php
declare(strict_types=1);

namespace Vending\Sales;

use DateTimeImmutable;

final class Sale
{
    /** @var DateTimeImmutable */
    private $time;

    /** @var string */
    private $customer;

    /** @var string */
    private $sku;

    /** @var string */
    private $name;

    /** @var int */
    private $price;

    public function __construct(DateTimeImmutable $time, string $customer, string $sku, string $name, int $price)
    {
        $this->time = $time;
        $this->customer = $customer;
        $this->sku = $sku;
        $this->name = $name;
        $this->price = $price;
    }

    public static function fromArray(array $data): self
    {
        return new self(new DateTimeImmutable($data['time']), $data['customer'], $data['sku'], $data['name'], (int)$data['price']);
    }

    public function toArray(): array
    {
        return [
            'time' => $this->time->format(DATE_ATOM),
            'customer' => $this->customer,
            'sku' => $this->sku,
            'name' => $this->name,
            'price' => $this->price,
        ];
    }

    public function time(): DateTimeImmutable
    {
        return $this->time;
    }

    public function day(): string
    {
        return $this->time->format('Y-m-d');
    }

    public function customer(): string
    {
        return $this->customer;
    }

    public function sku(): string
    {
        return $this->sku;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function price(): int
    {
        return $this->price;
    }
}
