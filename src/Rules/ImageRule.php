<?php

namespace Codemastercarlos\Receipt\Rules;

use Codemastercarlos\Receipt\Interfaces\Bootstrap\Validation\Rule;
use finfo;

class ImageRule implements Rule
{
    public function validate($value, $param = null): bool
    {
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $tmpFile = $value->getStream()->getMetadata('uri');
        $mimeType = $finfo->file($tmpFile);

        return (str_starts_with($mimeType, 'image/') && $value->getError() === UPLOAD_ERR_OK);
    }

    public function messageError($value, $param = null): string
    {
        return "O campo :attr deve ser uma imagem";
    }
}
