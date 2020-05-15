<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require __DIR__.'/includes/autoloader.inc.php';

$app->get('/', function (Request $request, Response $response, $args) {
    $response->getBody()->write("Hello world!");
    return $response;
});

$app->post('/signup', '\AuthController:signup');
$app->post('/login', '\AuthController:login');
$app->post('/user/update', '\UserController:update');
$app->post('/profile/update/{profile_id}', '\ProfileController:updateProfile');
$app->post('/portfolio/add', '\PortfolioController:add');
$app->post('/portfolio/update', '\PortfolioController:update');
$app->delete('/portfolio/delete/{id_portfolio}', '\PortfolioController:delete');