<?php

namespace Codemastercarlos\Receipt\Controller;

use Codemastercarlos\Receipt\Bootstrap\View;
use Codemastercarlos\Receipt\Repository\ReceiptRepository;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class SearchController implements RequestHandlerInterface
{
    use View;

    public function __construct(private readonly ReceiptRepository $repository)
    {
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $params = $request->getQueryParams();

        $search = filter_var($params['search'], FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => "/^.{1}/"]]);

        $receipts = $this->repository->searchFor($search);

        return new Response(200, body: $this->render('search', [
            "search" => $search,
            "receipts" => $receipts,
        ]));
    }

}
