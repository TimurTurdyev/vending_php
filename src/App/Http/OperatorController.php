<?php
declare(strict_types=1);

namespace App\Http;

use Framework\Http\HttpException;
use Framework\Http\Request;
use Framework\Http\Response;
use InvalidArgumentException;
use Vending\Machine;
use Vending\Operator;
use Vending\VendingException;
use Vending\World;

final class OperatorController extends Controller
{
    public function loginForm(Request $request): Response
    {
        return $this->page($request, $this->world(), 'operator/login.tpl');
    }

    public function login(Request $request): Response
    {
        $operator = $this->world->read()->operator((string)$request->post('login'));

        if ($operator === null || !$operator->verify((string)$request->post('password'))) {
            $this->flash(['error' => 'Неверный логин или пароль']);

            return $this->redirect($request, '/operator/login');
        }

        $this->session->set('operator', $operator->id());

        return $this->redirect($request, '/operator');
    }

    public function logout(Request $request): Response
    {
        $this->session->delete('operator');

        return $this->redirect($request, '/');
    }

    public function dashboard(Request $request): Response
    {
        $world = $this->world();
        $operator = $this->operator($world);

        return $this->page($request, $world, 'operator/dashboard.tpl', [
            'machines' => $world->machinesOf($operator),
            'today' => $this->clock->now()->format('Y-m-d'),
        ]);
    }

    public function machine(Request $request): Response
    {
        $world = $this->world();

        return $this->page($request, $world, 'operator/machine.tpl', [
            'machine' => $this->owned($world, $request),
            'today' => $this->clock->now()->format('Y-m-d'),
        ]);
    }

    public function restock(Request $request): Response
    {
        return $this->service($request, function (Machine $machine) use ($request) {
            $slot = (string)$request->post('slot');
            $added = $machine->restock($slot, $request->post('quantity') ? (int)$request->post('quantity') : null);

            return sprintf('В ячейку %s добавлено %d шт.', $slot, $added);
        });
    }

    public function load(Request $request): Response
    {
        return $this->service($request, function (Machine $machine) use ($request) {
            $slot = (string)$request->post('slot');
            $machine->load($slot, (string)$request->post('sku'), (int)$request->post('quantity'));

            return sprintf('Ячейка %s загружена', $slot);
        });
    }

    public function price(Request $request): Response
    {
        return $this->service($request, function (Machine $machine) use ($request) {
            $product = $machine->product((string)$request->post('sku'));
            $price = $machine->currency()->parse((string)$request->post('price'));
            $machine->setPrice($product->sku(), $price);

            return sprintf('%s теперь стоит %s', $product->name(), $machine->currency()->format($price));
        });
    }

    public function collect(Request $request): Response
    {
        return $this->service($request, function (Machine $machine) {
            return 'Выручка забрана: ' . $machine->currency()->format($machine->collect()->total());
        });
    }

    private function service(Request $request, callable $action): Response
    {
        try {
            $message = $this->world->transaction(function (World $world) use ($request, $action) {
                return $action($this->owned($world, $request));
            });
            $this->flash(['success' => $message]);
        } catch (VendingException | InvalidArgumentException $e) {
            $this->flash(['error' => $e->getMessage()]);
        }

        return $this->redirect($request, '/operator/machines/' . $request->attribute('id'));
    }

    private function operator(World $world): Operator
    {
        $operator = $this->operatorId() !== null ? $world->operator($this->operatorId()) : null;

        if ($operator === null) {
            throw HttpException::forbidden('Панель только для владельцев аппаратов, войдите под своим логином');
        }

        return $operator;
    }

    private function owned(World $world, Request $request): Machine
    {
        $id = (string)$request->attribute('id');

        if (!$world->hasMachine($id) || !$this->operator($world)->owns($world->machine($id))) {
            throw HttpException::notFound('Такого аппарата у вас нет');
        }

        return $world->machine($id);
    }
}
