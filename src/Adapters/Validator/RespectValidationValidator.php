<?php

namespace App\Adapters\Validator;

use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Factory;
use Respect\Validation\Validator as V;

class RespectValidationValidator implements ValidatorInterface
{
  private $validator;
  private $errors = [];

  public function setRules($rules)
  {
    $this->validator = new V();
    foreach($rules as $key => $rule) {
      $validatorRule = $this->createRule($rule, $key);
      $this->validator->addRule($validatorRule);
    }
  }

  public function validate($input)
  {
    try {
      return $this->validator->assert($input);
    } catch(NestedValidationException $ex) {
      $this->setErrors($ex->getMessages());
      throw new ValidationException($ex->getMessages());
    }
  }

  public function getErrors()
  {
    return $this->errors;
  }

  private function createRule($rule, $key = 0)
  {
    if (is_array($rule)) {
      $wrappedRules = [];
      foreach($rule as $wrapperRule) {
        $wrappedRules[] = $this->createRule($wrapperRule);
      }
      $validatorRule = Factory::getDefaultInstance()->rule($key, $wrappedRules);
      return $validatorRule;
    } 

    list($ruleName, $arguments) = $this->extractRuleParams($rule);    
    $validatorRule = Factory::getDefaultInstance()->rule($ruleName, $arguments);    
    return $validatorRule;
  }

  private function extractRuleParams($rule)
  {
    $ruleArr = explode(':', $rule);
    $ruleName = $ruleArr[0];
    $arguments = [];
    if (isset($ruleArr[1])) {
      $arguments = explode(',', $ruleArr[1]);
    }
    return [$ruleName, $arguments];
  }

  private function setErrors($messages)
  {
    $this->errors = $messages;
  }
}