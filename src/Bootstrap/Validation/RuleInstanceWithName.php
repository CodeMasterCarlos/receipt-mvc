<?php

namespace Codemastercarlos\Receipt\Bootstrap\Validation;

use Codemastercarlos\Receipt\Exception\InvalidValidationException;
use Codemastercarlos\Receipt\Interfaces\Bootstrap\Validation\Rule;

class RuleInstanceWithName
{
    private readonly Rule $rule;
    public ?string $ruleParam = null;
    private string $ruleName;

    /**
     * @throws InvalidValidationException
     */
    public function __construct(string|Rule $ruleValidation)
    {
        $this->rule = $ruleValidation instanceof Rule ? $ruleValidation : $this->getRuleByName($ruleValidation);
    }

    public function rule(): Rule
    {
        return $this->rule;
    }

    /**
     * @throws InvalidValidationException
     */
    private function getRuleByName(string $ruleValidation): Rule
    {
        $this->setValidationRuleNameAndParam($ruleValidation);

        $ruleClass = RuleService::getRule($this->ruleName);
        return new $ruleClass();
    }

    private function setValidationRuleNameAndParam($ruleValidation): void
    {
        $partsValidationInformation = explode(":", $ruleValidation);
        $this->ruleName = $partsValidationInformation[0];
        $this->ruleParam = $partsValidationInformation[1] ?? null;
    }
}
