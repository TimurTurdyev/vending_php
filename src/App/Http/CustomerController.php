<?php
declare(strict_types=1);

namespace App\Http;

use Framework\Http\Request;
use Framework\Http\Response;

// в одном браузере можно постоять у аппарата за разных покупателей
final class CustomerController extends Controller
{
    public function choose(Request $request): Response
    {
        $id = (string)$request->post('customer');

        if ($this->world->read()->hasCustomer($id)) {
            $this->session->set('customer', $id);
        }

        return $this->back($request);
    }

    public function create(Request $request): Response
    {
        $this->session->set('customer', $this->world->createGuest());

        return $this->back($request);
    }

    private function back(Request $request): Response
    {
        $path = (string)$request->post('back', '/');

        return $this->redirect($request, strpos($path, '/') === 0 && strpos($path, '//') !== 0 ? $path : '/');
    }
}
