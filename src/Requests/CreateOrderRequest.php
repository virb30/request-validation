<?php

namespace App\Requests;

class CreateOrderRequest extends FormRequest
{
  protected $stopOnFirstFailure = false;

  protected function rules()
  {
    return [
      'cpf' => ['digit:.,-', 'cpf'],
      'email' => ['email'],
      'products' => ['arrayType', 'length:1'],
      'coupon' => ['optional' => ['stringVal']],
    ];
  }
}