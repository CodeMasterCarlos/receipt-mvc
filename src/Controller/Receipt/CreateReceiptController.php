<?php

namespace Codemastercarlos\Receipt\Controller\Receipt;

use Codemastercarlos\Receipt\Bootstrap\View;
use Codemastercarlos\Receipt\Repository\ReceiptRepository;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class CreateReceiptController implements RequestHandlerInterface
{
    use View;

    public function __construct(private readonly ReceiptRepository $repository)
    {
    }

    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $user = $_SESSION['receipt']['user']['value'];

        $receipts = $this->repository->getAll($user['id']);

        return new Response(200, body: $this->render('create', ["name" => $user['name'], "receipts" => $receipts]));
    }
}
