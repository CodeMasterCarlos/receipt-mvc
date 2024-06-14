<?php

namespace Codemastercarlos\Receipt\Controller\Receipt;

use Codemastercarlos\Receipt\Bootstrap\FlasherMessage;
use Codemastercarlos\Receipt\Bootstrap\View;
use Codemastercarlos\Receipt\Helper\ValidationHelper;
use Codemastercarlos\Receipt\Repository\ReceiptRepository;
use Codemastercarlos\Receipt\Rules\ExistReceiptRule;
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
        $idUser = $_SESSION['receipt']['user']['value']['id'];

        $validation = new ValidationHelper($params, [
            'id' => ['required', 'int-receipt', new ExistReceiptRule($this->repository, $idUser)]
        ]);

        $id = $validation->getAttribute('id');
        $receipt = $this->repository->find($id, $idUser);

        return new Response(200, body: $this->render('edit', ["receipt" => $receipt]));
    }
}
