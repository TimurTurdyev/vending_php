<?php
declare(strict_types=1);

namespace App\Http;

use Framework\Http\HttpException;
use Framework\Http\Request;
use Framework\Http\Response;
use Vending\Operator;
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

    private function operator(World $world): Operator
    {
        $operator = $this->operatorId() !== null ? $world->operator($this->operatorId()) : null;

        if ($operator === null) {
            throw HttpException::forbidden('Панель только для владельцев аппаратов, войдите под своим логином');
        }

        return $operator;
    }
}
