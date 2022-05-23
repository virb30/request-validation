<?php

namespace App\Controllers;

use App\Adapters\Validator\RespectValidationValidator;
use App\Requests\CreateOrderRequest;
use Exception;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Request;

class OrderController
{
  protected $container;

  public function __construct(ContainerInterface $container)
  {
    $this->container = $container;
  }

  public function createOrder(ServerRequestInterface $request, ResponseInterface $response, $args)
  {
    $request = new CreateOrderRequest($request, $this->container->get('validator'));
    $body = $response->getBody();
    try {
      $data = $request->validated();
      
      // execute any business rule
      $processed = $data;

      $body->write(json_encode(['message' => 'success', 'data' => $processed]));
      return $response->withStatus(201)
        ->withBody($body)
        ->withHeader('Content-Type', 'application/json');
    } catch (Exception $ex) {
      $body->write($ex->getMessage());
      return $response->withStatus(400)
        ->withBody($body)
        ->withHeader('Content-Type', 'application/json');
    }
  }
}