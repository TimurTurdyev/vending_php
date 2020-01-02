<?php
declare(strict_types=1);

namespace App\Http;

use DateTimeImmutable;
use Framework\Http\HttpException;
use Framework\Http\Request;
use Framework\Http\Response;
use Vending\Customer;
use Vending\Machine;
use Vending\Money\Currency;
use Vending\Purchase;
use Vending\VendingException;
use Vending\World;

final class MachineController extends Controller
{
    public function index(Request $request): Response
    {
        $world = $this->world();

        return $this->page($request, $world, 'machines.tpl');
    }

    public function show(Request $request): Response
    {
        $world = $this->world();
        $machine = $this->machine($world, $request);
        $customer = $world->customer($this->customerId());
        $now = $this->clock->now();

        $quotes = [];

        foreach ($machine->slots() as $code => $slot) {
            $quotes[$code] = $machine->quote($slot, $customer, $now);
        }

        return $this->page($request, $world, 'machine.tpl', [
            'machine' => $machine,
            'quotes' => $quotes,
        ]);
    }

    public function insert(Request $request): Response
    {
        return $this->act($request, function (Machine $machine, Customer $customer, DateTimeImmutable $now) use ($request) {
            $machine->insert($customer, (int)$request->post('coin'));
        });
    }

    public function buy(Request $request): Response
    {
        return $this->act($request, function (Machine $machine, Customer $customer, DateTimeImmutable $now) use ($request) {
            return $machine->buy($customer, (string)$request->post('slot'), $now);
        });
    }

    public function refund(Request $request): Response
    {
        return $this->act($request, function (Machine $machine, Customer $customer) {
            return $machine->refund($customer);
        });
    }

    // после POST всегда редирект: обновление страницы не должно повторять покупку
    private function act(Request $request, callable $action): Response
    {
        $customerId = $this->customerId();

        try {
            $tray = $this->world->transaction(function (World $world) use ($request, $action, $customerId) {
                $machine = $this->machine($world, $request);
                $result = $action($machine, $world->customer($customerId), $this->clock->now());

                return $result instanceof Purchase ? $this->tray($result, $machine->currency()) : null;
            });

            if ($tray !== null) {
                $this->flash(['tray' => $tray]);
            }
        } catch (VendingException $e) {
            $this->flash(['error' => $e->getMessage()]);
        }

        return $this->redirect($request, '/machines/' . $request->attribute('id'));
    }

    private function machine(World $world, Request $request): Machine
    {
        $id = (string)$request->attribute('id');

        if (!$world->hasMachine($id)) {
            throw HttpException::notFound('Такого аппарата нет');
        }

        return $world->machine($id);
    }

    // лоток выдачи хранится в сессии до следующей страницы, поэтому сразу строками
    private function tray(Purchase $purchase, Currency $currency): array
    {
        $change = [];

        foreach ($purchase->change() as $coin => $count) {
            $change[] = ['coin' => $currency->format($coin), 'count' => $count];
        }

        $product = $purchase->product();
        $quote = $purchase->quote();

        return [
            'product' => $product ? $product->name() : null,
            'price' => $quote ? $currency->format($quote->price()) : null,
            'labels' => $quote ? $quote->labels() : [],
            'change' => $change,
            'total' => $currency->format($purchase->change()->total()),
        ];
    }
}
