<?php
declare(strict_types=1);

namespace App\Http;

use Framework\Http\Request;
use Framework\Http\Response;
use Framework\View\View;
use Vending\JsonFixtures;

final class VendingController
{
    /** @var JsonFixtures */
    private $fixtures;

    /** @var View */
    private $view;

    public function __construct(JsonFixtures $fixtures, View $view)
    {
        $this->fixtures = $fixtures;
        $this->view = $view;
    }

    public function index(Request $request): Response
    {
        return new Response($this->view->render('vending.tpl', [
            'products' => $this->fixtures->products(),
            'bank' => $this->fixtures->machine(),
            'wallet' => $this->fixtures->wallet(),
        ]));
    }
}
