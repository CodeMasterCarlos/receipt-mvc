<?php

namespace Codemastercarlos\Receipt\Controller\Receipt;

use Codemastercarlos\Receipt\Bootstrap\FlasherMessage;
use Codemastercarlos\Receipt\Entity\Receipt;
use Codemastercarlos\Receipt\Helper\HydrateHelper;
use Codemastercarlos\Receipt\Helper\ValidationHelper;
use Codemastercarlos\Receipt\Repository\ReceiptRepository;
use Codemastercarlos\Receipt\Services\ReceiptService;
use DateTimeImmutable;
use Exception;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class StoreReceiptController implements RequestHandlerInterface
{
    use FlasherMessage;

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
        $location = '/receipt/create';

        $files = $request->getUploadedFiles();

        $params['image'] = $files['image'];

        $validation = new ValidationHelper($params, [
            'title' => ['required', 'min:5', 'max:255'],
            'date' => ['required', 'date'],
            'image' => ['required', 'image'],
        ]);

        $title = HydrateHelper::hydrateString($validation->getAttribute('title'));
        $date = new DateTimeImmutable($validation->getAttribute('date'));
        $image = $validation->getAttribute('image');

        $receiptService = new ReceiptService();
        $imageName = $receiptService->createImage($image);

        $receipt = new Receipt($idUser, $title, $imageName, $date);
        $validationCreated = $this->repository->create($receipt);

        if ($validationCreated) {
            $this->flasherCreate("success", "O comprovante foi criado com sucesso!");
        } else {
            $this->flasherCreate("error", "Desculpe, não foi possível criar o comprovante neste momento.");
        }

        return new Response(302, ['Location' => $location]);
    }
}
