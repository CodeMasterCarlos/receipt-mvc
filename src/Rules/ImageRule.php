<?php

namespace Codemastercarlos\Receipt\Rules;

use Codemastercarlos\Receipt\Interfaces\Bootstrap\Validation\Rule;
use finfo;
use Nyholm\Psr7\UploadedFile;

class ImageRule implements Rule
{
    public function __construct(private readonly bool $required = true)
    {
    }

    public function validate($value, $param = null): bool
    {
        /** @var UploadedFile $value */
        $finfo = new finfo(FILEINFO_MIME_TYPE);

        if ($this->required === false && empty($value->getClientMediaType()) && $value->getSize() === 0
            && $value->getError() === 4) {
            return true;
        }

        $tmpFile = $value->getStream()->getMetadata('uri');
        $mimeType = $finfo->file($tmpFile);

        return (str_starts_with($mimeType, 'image/') && $value->getError() ===
                UPLOAD_ERR_OK);
    }

    public function messageError($value, $param = null): string
    {
        return "O campo :attr deve ser uma imagem";
    }
}
