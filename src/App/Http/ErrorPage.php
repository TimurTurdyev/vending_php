<?php
declare(strict_types=1);

namespace App\Http;

use Framework\Http\HttpException;
use Framework\Http\Request;
use Framework\Http\Response;
use Framework\View\View;

final class ErrorPage
{
    /** @var View */
    private $view;

    public function __construct(View $view)
    {
        $this->view = $view;
    }

    public function render(HttpException $e, Request $request): Response
    {
        return new Response($this->view->render('error.tpl', [
            'base' => $request->basePath(),
            'status' => $e->status(),
            'message' => $e->getMessage(),
        ]), $e->status());
    }
}
