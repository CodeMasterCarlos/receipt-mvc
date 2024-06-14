<?php

namespace Codemastercarlos\Receipt\Services;

use Psr\Http\Message\UploadedFileInterface;

class ReceiptService
{
    private string $pathStorage = __DIR__ .  "/../../public/storage/";

    public function createImage(UploadedFileInterface $image): string
    {
        $safeFileName = uniqid('upload_', true) . '_' . pathinfo($image->getClientFilename(), PATHINFO_BASENAME);
        $image->moveTo($this->pathStorage . $safeFileName);
        return $safeFileName;
    }

    public function deleteImage(string $imageName): bool
    {
        return unlink($this->pathStorage . $imageName);
    }
}