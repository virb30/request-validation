<?php

namespace App\Adapters\Validator;

interface ValidatorInterface
{
  public function setRules($rules);
  public function validate($input);
  public function getErrors();
}