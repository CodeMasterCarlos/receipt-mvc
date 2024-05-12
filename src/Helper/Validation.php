<?php

namespace Codemastercarlos\Receipt\Helper;

use Codemastercarlos\Receipt\Bootstrap\FlasherMessage;

class Validation
{
    use FlasherMessage;

    private array $params;

    private bool $error = false;

    public function __construct($params)
    {
        $this->params = $params;
    }

    public function validate($name, $filter, $options = [], $messageError = [], $persistValueSession = true): string|bool
    {
        $value = $this->params[$name];
        $attr = filter_var(trim($value), $filter, $options);
        if ($persistValueSession) {
            $_SESSION['receipt']['validation']['params'][$name] = $value;
        }

        if ($this->error === false && $attr === false) {
            $this->setError($messageError);
        }

        return $attr;
    }

    public function validationWasError(): bool
    {
        if ($this->error === true) {
            return true;
        }

        unset($_SESSION['receipt']['validation']['params']);
        return false;
    }

    public function setError(array $message): void
    {
        $this->messageError($message);
        $this->error = true;
    }

    private function messageError($messageError): void
    {
        $status = $messageError['status'] ?? "error";
        $message = $messageError['message'];
        $time = $messageError['time'] ?? 5000;

        $this->flasherCreate($status, $message, $time);
    }
}