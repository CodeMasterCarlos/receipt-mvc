<?php

namespace Codemastercarlos\Receipt\Controller\Receipt;

use Codemastercarlos\Receipt\Bootstrap\FlasherMessage;
use Codemastercarlos\Receipt\Bootstrap\View;
use Codemastercarlos\Receipt\Entity\Receipt;
use Codemastercarlos\Receipt\Helper\HydrateHelper;
use Codemastercarlos\Receipt\Helper\ValidationHelper;
use Codemastercarlos\Receipt\Repository\ReceiptRepository;
use Codemastercarlos\Receipt\Rules\ExistReceiptRule;
use Codemastercarlos\Receipt\Rules\ImageRule;
use Codemastercarlos\Receipt\Rules\NullableDateRule;
use Codemastercarlos\Receipt\Services\ReceiptService;
use DateTimeImmutable;
use Exception;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UploadedFileInterface;
use Psr\Http\Server\RequestHandlerInterface;

class UpdateReceiptController implements RequestHandlerInterface
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

        $files = $request->getUploadedFiles();

        $params['image'] = $files['image'];

        $validation = new ValidationHelper($params, [
            'id' => ['required', 'int-receipt', new ExistReceiptRule($this->repository, $idUser)],
            'title' => ['required', 'min:5', 'max:255'],
            'date' => [new NullableDateRule()],
            'image' => [new ImageRule(false)],
        ]);

        $id = $validation->getAttribute('id');
        $title = HydrateHelper::hydrateString($validation->getAttribute('title'));
        $date = new DateTimeImmutable($validation->getAttribute('date'));

        /** @var UploadedFileInterface $image */
        $image = $validation->getAttribute('image');
        $location = '/receipt/edit?id=' . $id;

        $receiptOld = $this->repository->find($id, $idUser);

        if ($image->getError() === 4) {
            $imagePath = $receiptOld->image;
        } else {
            $receiptService = new ReceiptService();
            $imagePath = $receiptService->createImage($image);
            $receiptService->deleteImage($receiptOld->image);
        }

        $receipt = new Receipt($idUser, $title, $imagePath, $date);
        $receipt->setId($id);
        $validationCreated = $this->repository->update($receipt);

        if ($validationCreated) {
            $this->flasherCreate("success", "O comprovante foi atualizado com sucesso!");
        } else {
            $this->flasherCreate("error", "Desculpe, não foi possível atualizar o comprovante neste momento.");
        }

        return new Response(302, ['Location' => $location]);
    }
}
