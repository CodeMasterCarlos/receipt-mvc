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

        $validation = new ValidationHelper($params, [
            'id' => ['required', 'int-receipt', new ExistReceiptRule($this->repository, $idUser)]
        ]);

        $id = $validation->getAttribute('id');
        $receipt = $this->repository->find($id, $idUser);

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
