<?php
declare(strict_types=1);

namespace App\Http;

use Framework\Http\Request;
use Framework\Http\Response;
use Framework\Storage\Storage;
use Framework\View\View;
use Vending\Coins;
use Vending\Product;
use Vending\Purchase;
use Vending\Simulation;
use Vending\SimulationRepository;
use Vending\VendingException;

final class VendingController
{
    private const FLASH = 'flash';

    /** @var SimulationRepository */
    private $repository;

    /** @var View */
    private $view;

    /** @var Storage */
    private $storage;

    public function __construct(SimulationRepository $repository, View $view, Storage $storage)
    {
        $this->repository = $repository;
        $this->view = $view;
        $this->storage = $storage;
    }

    public function index(Request $request): Response
    {
        $flash = $this->storage->get(self::FLASH) ?: [];
        $this->storage->delete(self::FLASH);

        if (isset($flash['purchase'])) {
            return new Response($this->view->render('vending_success.tpl', [
                'base' => $request->basePath(),
                'product' => $flash['purchase']['product'] ? Product::fromArray($flash['purchase']['product']) : null,
                'change' => new Coins($flash['purchase']['change']),
            ]));
        }

        $simulation = $this->repository->load();
        $machine = $simulation->machine();

        return new Response($this->view->render('vending.tpl', [
            'base' => $request->basePath(),
            'products' => $machine->products(),
            'credit' => $machine->credit(),
            'bank' => $machine->bank(),
            'wallet' => $simulation->wallet(),
            'error' => $flash['error'] ?? '',
        ]));
    }

    public function insert(Request $request): Response
    {
        return $this->perform($request, function (Simulation $simulation) use ($request) {
            $simulation->insert((int)$request->post('coin'));
        });
    }

    public function refund(Request $request): Response
    {
        return $this->perform($request, function (Simulation $simulation) {
            return $simulation->refund();
        });
    }

    // после POST всегда редирект: обновление страницы не должно повторять действие
    private function perform(Request $request, callable $action): Response
    {
        $simulation = $this->repository->load();

        try {
            $purchase = $action($simulation);
            $this->repository->save($simulation);

            if ($purchase instanceof Purchase) {
                $this->storage->set(self::FLASH, ['purchase' => [
                    'product' => $purchase->product() ? $purchase->product()->toArray() : null,
                    'change' => $purchase->change()->toArray(),
                ]]);
            }
        } catch (VendingException $e) {
            $this->storage->set(self::FLASH, ['error' => $e->getMessage()]);
        }

        return Response::redirect($request->basePath() . '/');
    }
}
