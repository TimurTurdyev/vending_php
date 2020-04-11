<?php
declare(strict_types=1);

namespace App\Http;

use Framework\Clock\Clock;
use Framework\Http\Csrf;
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

    /** @var Csrf */
    protected $csrf;

    public function __construct(View $view, Storage $session, WorldRepository $world, Clock $clock, Csrf $csrf)
    {
        $this->view = $view;
        $this->session = $session;
        $this->world = $world;
        $this->clock = $clock;
        $this->csrf = $csrf;
    }

    protected function page(Request $request, World $world, string $template, array $data = []): Response
    {
        $operatorId = $this->operatorId();

        return new Response($this->view->render($template, $data + [
            'base' => $request->basePath(),
            'path' => $request->path(),
            'csrf' => $this->csrf->token(),
            'flash' => $this->pullFlash(),
            'world' => $world,
            'customer' => $world->customer($this->customerId()),
            'operator' => $operatorId !== null ? $world->operator($operatorId) : null,
        ]));
    }

    protected function redirect(Request $request, string $path): Response
    {
        return Response::redirect($request->basePath() . $path);
    }

    // мир для страницы: сначала гость, если он пришел впервые
    protected function world(): World
    {
        $this->customerId();

        return $this->world->read();
    }

    protected function customerId(): string
    {
        $id = $this->session->get('customer');

        if (!is_string($id) || !$this->world->read()->hasCustomer($id)) {
            $id = $this->world->createGuest();
            $this->session->set('customer', $id);
        }

        return $id;
    }

    protected function operatorId(): ?string
    {
        $id = $this->session->get('operator');

        return is_string($id) ? $id : null;
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
