<?php

namespace App\Requests;

use App\Adapters\Validator\ValidationException;
use App\Adapters\Validator\ValidatorInterface;
use Exception;
use Psr\Http\Message\ServerRequestInterface;

class FormRequest
{
  protected $stopOnFirstFailure = false;
  
  private $data;
  private $errors = [];
  private ValidatorInterface $validator;

  public function __construct(ServerRequestInterface $request, ValidatorInterface $validator)
  {
    $this->data = array_merge($request->getParsedBody(), $request->getQueryParams());
    $this->validator = $validator;
  }

  public function validate()
  {
    $rules = $this->rules();
    foreach($rules as $field => $fieldRules) {
      $this->validator->setRules($fieldRules);
      try {
        $this->validator->validate($this->getFieldData($field));
      } catch (ValidationException $ex) {        
        $this->addError($field, $this->validator->getErrors());
        if ($this->stopOnFirstFailure) {
          throw new Exception(json_encode($this->errors));
        }
      }
    }
    if(!$this->hasPassed()) {
      throw new Exception(json_encode($this->errors));
    }
  }

  public function validated()
  {
    $this->validate();
    return $this->data;
  }

  protected function rules()
  {
    return [];
  }

  private function hasPassed()
  {
    return empty($this->errors);
  }

  private function addError($field, $messages)
  {
    $this->errors[$field] = $messages;
  }

  private function getFieldData($key, $default = null)
  {
    $result = $default;
    if (is_array($this->data) && isset($this->data[$key])) {
      $result = $this->data[$key];
    } elseif (is_object($this->data) && property_exists($this->data, $key)) {
      $result = $this->data->$key;
    }

    return $result;
  }
}