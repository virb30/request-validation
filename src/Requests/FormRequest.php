<?php

namespace App\Requests;

use Exception;
use Psr\Http\Message\ServerRequestInterface;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Factory;
use Respect\Validation\Validator;

class FormRequest
{
  private $data;
  protected $allErrors = false;
  private $errors = [];

  public function __construct(ServerRequestInterface $request)
  {
    $this->data = array_merge($request->getParsedBody(), $request->getQueryParams());
  }

  public function getValidator(): Validator
  {
    return Validator::create();
  }

  public function validate()
  {
    $rules = $this->rules();
    foreach($rules as $field => $validator) {
      try {
        $validator->assert($this->getField($field));
      } catch (NestedValidationException $ex) {        
        if (!$this->allErrors) {
          throw $ex;
        }
        $this->setErrors($field, $ex->getMessages());
      }
    }

    if(!$this->hasPassed()) {
      throw new Exception(json_encode($this->errors));
    }
  }

  private function hasPassed()
  {
    return empty($this->errors);
  }

  private function setErrors($field, $messages)
  {
    $this->errors[$field] = $messages;
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

  private function getField($key, $default = null)
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