<?php

namespace Codemastercarlos\Receipt\Bootstrap;

class ValidationRequest
{
    public function saveErrors(array $erros): void
    {
        foreach ($erros as $erro) {
            $this->saveError($erro);
        }
    }

    public function saveError(array $erro): void
    {
        $this->session()[$erro['attribute']] = $erro['message'];
    }

    public function getError(string $attribute): ?string
    {
        return $this->session()[$attribute] ?? null;
    }

    private function &session(): array
    {
        if (isset($_SESSION['validate']['flash']) === false) {
            $_SESSION['validate']['flash'] = [];
        }

        return $_SESSION['validate']['flash'];
    }
}
