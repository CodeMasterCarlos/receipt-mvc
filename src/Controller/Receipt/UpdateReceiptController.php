<?php

namespace Codemastercarlos\Receipt\Controller\Receipt;

use Codemastercarlos\Receipt\Bootstrap\FlasherMessage;
use Codemastercarlos\Receipt\Bootstrap\View;
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

        $id = $this->validateId($params);
        if ($id === false) {
            return new Response(303, ['Location' => '/']);
        }

        $location = '/receipt/edit?id=' . $id;

        $files = $request->getUploadedFiles();
        /**
         * @var Validation $validation
         * @var UploadedFileInterface|bool $image
         */
        [$title, $date, $image, $validation] = $this->validateParams($params, $files['image'], false);

        if ($validation->validationWasError()) {
            return new Response(303, ['Location' => $location]);
        }

        $receiptOld = $this->repository->find($id, $idUser);

        if ($receiptOld === false) {
            $this->flasherCreate("info", "Comprovante não existe.");
            return new Response(302, ['Location' => "/"]);
        }

        $imagePath = false;
        if ($image !== false) {
            $imagePath = $this->createImage($image);
        }

        if ($imagePath === false) {
            $imagePath = $receiptOld->image;
        } else {
            $this->deleteImage($receiptOld->image);
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

    private function validateId($params): int|bool
    {
        $id = filter_var($params['id'], FILTER_VALIDATE_INT);

        if ($id === false) {
            $this->flasherCreate("error", "Informe um comprovante válido.");
        }

        return $id;
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

    private function deleteImage(string $imageName): bool
    {
        return unlink(__DIR__ . '/../../../public/storage/' . $imageName);
    }
}
