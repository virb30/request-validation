<?php

require __DIR__."/../vendor/autoload.php";

use App\Adapters\Validator\RespectValidationValidator;
use App\Controllers\OrderController;

$app = new \Slim\App();

$app->get('/', function($req, $res, $args) {
  $body = $req->getBody();
  $body->write(json_encode(['message' => 'Hello World!']));
  return $res->withStatus(200)->withBody($body);

});

$app->post('/orders', OrderController::class.':createOrder');

$container = $app->getContainer();
$container['validator'] = function ($container) {
    $validator = new RespectValidationValidator();
    return $validator;
};

$app->run();