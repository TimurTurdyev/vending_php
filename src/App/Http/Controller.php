<?php
declare(strict_types=1);

namespace App\Http;

use Framework\Clock\Clock;
use Framework\Http\Request;
use Framework\Http\Response;
use Framework\Storage\Storage;
use Framework\View\View;
use Vending\World;
use Vending\WorldRepository;

abstract class Controller
{
    private const FLASH = 'flash';

    /** @var View */
    protected $view;

    /** @var Storage */
    protected $session;

    /** @var WorldRepository */
    protected $world;

    /** @var Clock */
    protected $clock;

    public function __construct(View $view, Storage $session, WorldRepository $world, Clock $clock)
    {
        $this->view = $view;
        $this->session = $session;
        $this->world = $world;
        $this->clock = $clock;
    }

    protected function page(Request $request, World $world, string $template, array $data = []): Response
    {
        return new Response($this->view->render($template, $data + [
            'base' => $request->basePath(),
            'flash' => $this->pullFlash(),
            'world' => $world,
            'customer' => $world->customer($this->customerId()),
        ]));
    }

    protected function redirect(Request $request, string $path): Response
    {
        return Response::redirect($request->basePath() . $path);
    }

    protected function world(): World
    {
        return $this->world->read();
    }

    // у аппаратов пока один покупатель
    protected function customerId(): string
    {
        return 'guest';
    }

    protected function flash(array $flash): void
    {
        $this->session->set(self::FLASH, $flash);
    }

    private function pullFlash(): array
    {
        $flash = $this->session->get(self::FLASH) ?: [];
        $this->session->delete(self::FLASH);

        return $flash;
    }
}
