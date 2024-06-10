<?php

namespace Codemastercarlos\Receipt\Helper;

use Codemastercarlos\Receipt\Bootstrap\Validation\RuleInstanceWithName;
use Codemastercarlos\Receipt\Bootstrap\Validation\RuleService;
use Codemastercarlos\Receipt\Exception\InvalidValidationException;
use Codemastercarlos\Receipt\Interfaces\Bootstrap\Validation\Rule;

class RuleHelper
{
    private readonly Rule $rule;
    private readonly ?string $attributeInNaturalLanguage;
    private readonly mixed $ruleParam;
    private readonly mixed $ruleValue;
    private readonly bool $ruleValidate;

    /**
     * @throws InvalidValidationException
     */
    public function __construct(
        string|Rule $rule,
        string $attribute,
        mixed $value,
    )
    {
        $ruleInstanceWithName = new RuleInstanceWithName($rule);
        $this->rule = $ruleInstanceWithName->rule();

        $this->attributeInNaturalLanguage = RuleService::getRuleAttributeInNaturalLanguage($attribute);

        $this->ruleParam = $ruleInstanceWithName->ruleParam;
        $this->ruleValue = $value;

        $this->ruleValidate = $this->rule->validate($this->ruleValue, $this->ruleParam);
    }

    public function isValid(): bool
    {
        return $this->ruleValidate;
    }

    public function messageError(): string
    {
        return str_replace(":attr", $this->attributeInNaturalLanguage, $this->rule->messageError($this->ruleValue,
            $this->ruleParam));
    }
}
