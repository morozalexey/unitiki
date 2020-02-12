<?php

if( !session_id() ) @session_start();
require '../vendor/autoload.php';

use DI\ContainerBuilder;
use Delight\Auth\Auth;
use Aura\SqlQuery\QueryFactory;
use League\Plates\Engine;

$containerBuilder = new ContainerBuilder;

$containerBuilder->addDefinitions([
    Engine::class => function() {
        return new Engine('../app/views');
    },
    PDO::class => function() {
        return new PDO('mysql:host=localhost;dbname=unitiki', 'mysql', 'mysql');
    },
    Auth::class => function($container) {
        return new Auth($container->get('PDO'));
    },
    QueryFactory::class => function() {
        return new QueryFactory('mysql');
    }
]);

$container = $containerBuilder->build();

$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {
    $r->addRoute('GET', '/', ['App\controllers\PagesController', 'index']);
    $r->addRoute('GET', '/post_page', ['App\controllers\PagesController', 'post_page']);
    $r->addRoute('GET', '/login_page', ['App\controllers\PagesController', 'login_page']);
    $r->addRoute('GET', '/registration_page', ['App\controllers\PagesController', 'registration_page']);
    $r->addRoute('POST', '/registration', ['App\controllers\MainController', 'registration']);
    $r->addRoute('POST', '/login', ['App\controllers\MainController', 'login']);
    $r->addRoute('GET', '/logout', ['App\controllers\MainController', 'logout']);
    $r->addRoute('GET', '/verify_email', ['App\controllers\MainController','email_verification']);
    $r->addRoute('GET', '/admin', ['App\controllers\PagesController', 'admin']);
    $r->addRoute('GET', '/show', ['App\controllers\MainController', 'show']);
    $r->addRoute('GET', '/hide', ['App\controllers\MainController', 'hide']);
    $r->addRoute('GET', '/delete', ['App\controllers\MainController', 'delete']);
    $r->addRoute('POST', '/new_post', ['App\controllers\MainController', 'new_post']);
    $r->addRoute('POST', '/change_post', ['App\controllers\MainController', 'change_post']);
    $r->addRoute('GET', '/category', ['App\controllers\PagesController', 'category']);    
});

// Fetch method and URI from somewhere
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Strip query string (?foo=bar) and decode URI
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        echo "404 Not Found";
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        echo "405 Method Not Allowed";       
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        //$controller = new $handler[0];
        //call_user_func([$controller, $handler[1]], $vars);
        $container->call($routeInfo[1], $routeInfo[2]);
        break;
}