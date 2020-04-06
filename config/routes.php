<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

include __DIR__.'/includes/autoloader.inc.php';

$app->get('/', function (Request $request, Response $response, $args) {
    $response->getBody()->write("Hello world!");
    return $response;
});

$app->get('/login/{username}/{password}', function (Request $request, Response $response, array $args) {
    $login = new AuthController();
    $res = $login->getData($args['username'],$args['password']);
    $response->getBody()->write($res);
    return $response;
});