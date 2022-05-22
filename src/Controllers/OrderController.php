<?php

namespace App\Controllers;

use App\Requests\CreateOrderRequest;
use Exception;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Request;

class OrderController
{
  public function createOrder(Request $request, ResponseInterface $response, $args)
  {
    $request = new CreateOrderRequest($request);
    $body = $response->getBody();
    try {
      $data = $request->validated();
      $body->write(json_encode(['message' => 'success', 'data' => $data]));
      return $response->withStatus(201)->withBody($body);
    } catch (Exception $ex) {
      $body->write($ex->getMessage());
      return $response->withStatus(400)->withBody($body);
    }
  }
}