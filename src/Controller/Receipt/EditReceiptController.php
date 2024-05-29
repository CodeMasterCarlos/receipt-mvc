<?php

namespace Codemastercarlos\Receipt\Controller\Receipt;

use Codemastercarlos\Receipt\Bootstrap\FlasherMessage;
use Codemastercarlos\Receipt\Bootstrap\View;
use Codemastercarlos\Receipt\Repository\ReceiptRepository;
use Exception;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class EditReceiptController implements RequestHandlerInterface
{
    use View, FlasherMessage;

    public function __construct(private readonly ReceiptRepository $repository)
    {
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     * @throws Exception
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $params = $request->getQueryParams();

        $id = $this->validateId($params);
        $idUser = $_SESSION['receipt']['user']['value']['id'];
        if ($id === false) {
            return new Response(303, ['Location' => '/']);
        }

        $receipt = $this->repository->find($id, $idUser);

        if ($receipt === false) {
            $this->flasherCreate("info", "Comprovante não existe.");
            return new Response(302, ['Location' => "/"]);
        }

        return new Response(200, body: $this->render('edit', ["receipt" => $receipt,]));
    }

    private function validateId($params): int|bool
    {
        $id = filter_var($params['id'], FILTER_VALIDATE_INT);

        if ($id === false) {
            $this->flasherCreate("error", "Informe um comprovante válido.");
        }

        return $id;
    }
}
