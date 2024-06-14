<?php

namespace Codemastercarlos\Receipt\Rules;

use Codemastercarlos\Receipt\Interfaces\Bootstrap\Validation\Rule;
use Codemastercarlos\Receipt\Repository\ReceiptRepository;
use Exception;

class ExistReceiptRule implements Rule
{
    public function __construct(private readonly ReceiptRepository $repository, private readonly int $idUser)
    {
    }

    /**
     * @throws Exception
     */
    public function validate($value, $param = null): bool
    {
        $receipt = $this->repository->find($value, $this->idUser);
        return $receipt !== false;
    }

    public function messageError($value, $param = null): string
    {
        return "Comprovante não existe.";
    }
}
