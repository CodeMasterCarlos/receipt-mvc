<?php

namespace Codemastercarlos\Receipt\Controller\Receipt;

use Codemastercarlos\Receipt\Bootstrap\FlasherMessage;
use Codemastercarlos\Receipt\Entity\Receipt;
use Codemastercarlos\Receipt\Helper\Validation;
use Codemastercarlos\Receipt\Repository\ReceiptRepository;
use DateTimeImmutable;
use Exception;
use finfo;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UploadedFileInterface;
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
        /**
         * @var Validation $validation
         * @var UploadedFileInterface $image
         */
        [$title, $date, $image, $validation] = $this->validateParams($params, $files['image']);

        if ($validation->validationWasError()) {
            return new Response(303, ['Location' => $location]);
        }

        $safeFileName = $this->createImage($image);
        if ($safeFileName === false) {
            $this->flasherCreate("error", "Por favor, envie uma imagem.");
            return new Response(303, ['Location' => $location]);
        }

        $receipt = new Receipt($idUser, $title, $safeFileName, $date);
        $validationCreated = $this->repository->create($receipt);

        if ($validationCreated) {
            $this->flasherCreate("success", "O comprovante foi criado com sucesso!");
        } else {
            $this->flasherCreate("error", "Desculpe, não foi possível criar o comprovante neste momento.");
        }

        return new Response(302, ['Location' => $location]);
    }

    /**
     * @param array $params
     * @param UploadedFileInterface $fileImage
     * @param bool $imageIsRequired
     * @return array
     * @throws Exception
     */
    private function validateParams(array $params, UploadedFileInterface $fileImage, bool $imageIsRequired = true): array
    {
        $validation = new Validation($params);

        $title = $validation->validate('title', FILTER_VALIDATE_REGEXP, ["options" => ["regexp" => "/^.{3}?.*/"]], ["message" => "O título deve ter pelo menos 3 caracteres."]);

        $date = $params['date'];

        $dateString = empty($date) === false ? $validation->validate('date', FILTER_VALIDATE_REGEXP, ["options" => ["regexp" => "/^\d{4}-\d{2}-\d{2}/"]], ["message" => "Data inválida. Por favor, tente novamente."]) : "";

        $date = new DateTimeImmutable($dateString);

        if ($fileImage->getError() === UPLOAD_ERR_OK) {
            $image = $fileImage;
        } else {
            $image = false;
            if ($imageIsRequired) {
                $validation->setError(["message" => "Por favor, envie uma imagem válida."]);
            }
        }

        return [$title, $date, $image, $validation];
    }

    /**
     * @throws Exception
     */
    private function createImage(UploadedFileInterface $image): string|false
    {
        $isImage = true;

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $tmpFile = $image->getStream()->getMetadata('uri');
        $mimeType = $finfo->file($tmpFile);

        if (str_starts_with($mimeType, 'image/') === false) {
            $isImage = false;
        }

        $safeFileName = uniqid('upload_', true) . '_' . pathinfo($image->getClientFilename(), PATHINFO_BASENAME);
        $image->moveTo(__DIR__ . '/../../../public/storage/' . $safeFileName);
        return $isImage ? $safeFileName : false;
    }
}
