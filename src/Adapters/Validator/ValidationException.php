<?php

namespace App\Adapters\Validator;

use Exception;

class ValidationException extends Exception
{
  public function __construct($message)
  {
    parent::__construct(json_encode($message));
  }
}