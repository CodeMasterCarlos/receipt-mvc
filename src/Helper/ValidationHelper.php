<?php

namespace Codemastercarlos\Receipt\Helper;

use Codemastercarlos\Receipt\Bootstrap\ValidationRequest;
use Codemastercarlos\Receipt\Exception\InvalidValidationException;
use Codemastercarlos\Receipt\Interfaces\Bootstrap\Validation\Rule;

class ValidationHelper
{
    private readonly array $parameters;
    private readonly array $listValidations;
    private array $errors = [];

    /**
     * @throws InvalidValidationException
     */
    public function __construct(array $parameters, array $listValidations)
    {
        $this->parameters = $parameters;
        $this->listValidations = $listValidations;

        $this->validate();

        $this->backToValidationFormWithError();
    }

    public function getAttribute(string $attribute): mixed
    {
        return $this->parameters[$attribute] ?? null;
    }

    /**
     * @throws InvalidValidationException
     */
    private function validate(): void
    {
        foreach ($this->listValidations as $attribute => $rules) {
            $this->validateListRules($attribute, $rules);
        }
    }

    /**
     * @throws InvalidValidationException
     */
    private function validateListRules(string $attribute, array $rules): void
    {
        foreach ($rules as $ruleName) {
            $this->validateRule($attribute, $ruleName);
        }
    }

    /**
     * @throws InvalidValidationException
     */
    private function validateRule(string $attribute, string|Rule $ruleName): void
    {
        $parameterValue = $this->parameters[$attribute] ?? null;

        $rule = new RuleHelper($ruleName, $attribute, $parameterValue);

        if ($rule->isValid() === false) {
            $this->errors[] = ["attribute" => $attribute, "message" => $rule->messageError()];
        }
    }

    /**
     * @throws InvalidValidationException
     */
    private function backToValidationFormWithError(): void
    {
        if (count($this->errors) === 0) {
            return;
        }

        $validationRequest = new ValidationRequest();
        $validationRequest->saveErrors($this->errors);

        throw new InvalidValidationException("Formulário inválido.");
    }
}
