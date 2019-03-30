<?php
require_once "../vendor/autoload.php";

use Illuminate\Database\Capsule\Manager as Capsule;
use Aura\Router\RouterContainer;

session_start();

$capsule = new Capsule;

$capsule->addConnection([
    'driver'    => 'mysql',
    'host'      => 'localhost',
    'database'  => 'platzi-php',
    'username'  => 'root',
    'password'  => '',
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
]);
// Make this Capsule instance available globally via static methods... (optional)
$capsule->setAsGlobal();
// Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
$capsule->bootEloquent();

$request = Zend\Diactoros\ServerRequestFactory::fromGlobals(
    $_SERVER,
    $_GET,
    $_POST,
    $_COOKIE,
    $_FILES
);

$routerContainer = new RouterContainer();
$map = $routerContainer->getMap();
$map->get('index', '/', [
    'controller' => 'App\Controllers\IndexController',
    'action' => 'indexAction',
]);

$map->get('addJob', '/jobs/add', [
    'controller' => 'App\Controllers\JobsController',
    'action' => 'getAddJob',
    'auth' => true,
]);
$map->post('saveJob', '/jobs/add', [
    'controller' => 'App\Controllers\JobsController',
    'action' => 'getAddJob',
    'auth' => true,
]);

$map->get('addUser', '/users/add', [
    'controller' => 'App\Controllers\UsersController',
    'action' => 'getAddUser',
    'auth' => true,
]);
$map->post('saveUser', '/users/add', [
    'controller' => 'App\Controllers\UsersController',
    'action' => 'postSaveUser',
    'auth' => true,
]);

$map->get('login', '/login', [
    'controller' => 'App\Controllers\AuthController',
    'action' => 'getLogin',
]);
$map->get('logout', '/logout', [
    'controller' => 'App\Controllers\AuthController',
    'action' => 'getLogout',
]);
$map->post('auth', '/auth', [
    'controller' => 'App\Controllers\AuthController',
    'action' => 'postAuth',
]);

$map->get('admin', '/admin', [
    'controller' => 'App\Controllers\AdminController',
    'action' => 'getIndex',
    'auth' => true,
]);

$matcher = $routerContainer->getMatcher();
$route = $matcher->match($request);

if (!$route) {
    echo "No hay una ruta definida.";
} else {
    $handlerData = $route->handler;
    $controllerName = $handlerData['controller'];
    $actionName = $handlerData['action'];
    $needsAuth = $handlerData['auth'] ?? false;

    $controller = new $controllerName;
    $response = $controller->$actionName($request);
    $authUserId = $_SESSION['userId'] ?? false;

    if ($needsAuth && !$authUserId) {
        echo "Protected route";
        return;
    }

    foreach ($response->getHeaders() as $name => $values) {
        foreach ($values as $value) {
            header(sprintf('%s: %s', $name, $value), false);
        }
    }

    http_response_code($response->getStatusCode());

    echo $response->getBody();
}
