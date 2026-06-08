<?php

require __DIR__ . '/../../vendor/autoload.php';

use Paw\Core\Config;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\JsonFormatter;

use Dotenv\Dotenv;

use Paw\Core\Router;
use Paw\Core\Request;

use Paw\Core\Database\ConnectionBuilder;

use Paw\Core\ControllerFactory;

session_start();

$dotenv = Dotenv::createUnsafeImmutable(__DIR__ . '/../../');
$dotenv->safeLoad();
$config = new Config;

$request = new Request;

$log = new Logger('pawprints-app');
$handler = new StreamHandler($config->get("LOG_PATH"));
$handler->setLevel($config->get("LOG_LEVEL"));
$handler->setFormatter(new JsonFormatter());
$log->pushHandler($handler);

$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->register();

$connectionBuilder = new ConnectionBuilder;
$connectionBuilder->setLogger($log);
$connection = $connectionBuilder->make($config);

$controllerFactory = new ControllerFactory($request, $log, $connection);

$router=new Router;
$router->setLogger($log);
$router->setControllerFactory($controllerFactory);

$router->get('/','PageController@index');
$router->get('/sobreNosotros', 'PageController@sobreNosotros');
$router->get('/reserva', 'ReservaController@reserva');
$router->post('/reserva/procesar-reserva', 'ReservaController@procesarReserva');
$router->get('/reserva-exitosa', 'PageController@reservaExitosa');
$router->get('/pedidos', 'ReservaController@pedidos');
$router->get('/catalogo', 'LibroController@catalogo');
$router->get('/api/libros', 'LibroController@apiLibros');
$router->get('/crear-libro', 'LibroController@create');
$router->post('/crear-libro', 'LibroController@store');
$router->get('/detalle', 'LibroController@detalle');
$router->get('/buscar', 'LibroController@buscar');
$router->post('/agregarCarrito', 'CarritoController@agregar');
$router->post('/actualizarCarrito', 'CarritoController@actualizar');
$router->post('/vaciarCarrito', 'CarritoController@vaciar');
$router->post('/register', 'AuthController@register');
$router->get('/register', 'PageController@index');
$router->post('/login', 'AuthController@login');
$router->get('/login', 'PageController@index');
$router->get('/logout', 'AuthController@logout');

