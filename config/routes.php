<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

include __DIR__.'/includes/autoloader.inc.php';

$app->get('/', function (Request $request, Response $response, $args) {
    $response->getBody()->write("Hello world!");
    return $response;
});

$app->post('/signup', '\AuthController:signup');
$app->post('/login', '\AuthController:login');
$app->post('/updateUser', '\UserController:updateUser');
$app->post('/updateProfile/{profile_id}', '\ProfileController:updateProfile');
