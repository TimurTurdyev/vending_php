<?php
declare(strict_types=1);

namespace App\Http;

use Framework\Http\Request;
use Framework\Http\Response;

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

        return $this->redirect($request, '/');
    }

    public function logout(Request $request): Response
    {
        $this->session->delete('operator');

        return $this->redirect($request, '/');
    }
}
