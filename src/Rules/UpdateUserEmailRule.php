<?php

namespace Codemastercarlos\Receipt\Rules;

use Codemastercarlos\Receipt\Interfaces\Bootstrap\Validation\Rule;
use Codemastercarlos\Receipt\Repository\UserRepository;
use Exception;

class UpdateUserEmailRule implements Rule
{
    private readonly NoExistUserEmailRule $rule;

    public function __construct(private readonly UserRepository $repository, private readonly string $loggedUserEmail)
    {
        $this->rule = new NoExistUserEmailRule($this->repository);
    }

    /**
     * @throws Exception
     */
    public function validate($value, $param = null): bool
    {
        if ($value === $this->loggedUserEmail) {
            return true;
        }

        return $this->rule->validate($value, $param);
    }

    public function messageError($value, $param = null): string
    {
        return $this->rule->messageError($value, $param);
    }
}
