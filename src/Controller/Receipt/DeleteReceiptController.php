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

class DeleteReceiptController implements RequestHandlerInterface
{
    use View, FlasherMessage;

    public function __construct(private readonly ReceiptRepository $repository)
    {
    }

    /**
     * @throws Exception
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $params = $request->getParsedBody();
        $idUser = $_SESSION['receipt']['user']['value']['id'];

        $id = filter_var($params['id'], FILTER_VALIDATE_INT);

        if ($id === false) {
            $this->flasherCreate("error", "Informe um comprovante válido.");
            return new Response(303, ['Location' => '/']);
        }

        $receipt = $this->repository->find($id, $idUser);

        if ($receipt === false) {
            $this->flasherCreate("info", "Comprovante não existe.");
            return new Response(302, ['Location' => "/"]);
        }

        $validationDestroy = $this->repository->destroy($idUser, $id);

        if ($validationDestroy) {
            $this->deleteImage($receipt->image);
            $this->flasherCreate("success", "Comprovante removido com sucesso!");
        } else {
            $this->flasherCreate("success", "Não foi possível remover o comprovante.");
        }

        return new Response(302, ["Location" => "/"]);
    }

    private function deleteImage(string $imageName): bool
    {
        return unlink(__DIR__ . '/../../../public/storage/' . $imageName);
    }
}
