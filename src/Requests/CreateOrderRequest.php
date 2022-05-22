<?php

namespace App\Requests;

class CreateOrderRequest extends FormRequest
{
  protected $allErrors = true;

  protected function rules()
  {
    return [
      'cpf' => $this->getValidator()
        ->cpf(),
      'email' => $this->getValidator()
        ->email(),
      'products' => $this->getValidator()
        ->arrayType()
        ->length(1, null),
      'coupon' => $this->getValidator()
        ->optional($this->getValidator()
          ->stringVal())
    ];
  }
}