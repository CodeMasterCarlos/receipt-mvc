<?php

namespace Codemastercarlos\Receipt\Rules;

use Codemastercarlos\Receipt\Interfaces\Bootstrap\Validation\Rule;
use Codemastercarlos\Receipt\Repository\UserRepository;

class UniqueEmailUser implements Rule
{
    public function __construct(private readonly UserRepository $repository)
    {
    }

    public function validate($value, $param = null): bool
    {
        $user = $this->repository->getFromEmail($value);
        return $user === false;
    }

    public function messageError($value, $param = null): string
    {
        return "E-mail já cadastrado. Por favor, use um endereço de e-mail diferente.";
    }
}
