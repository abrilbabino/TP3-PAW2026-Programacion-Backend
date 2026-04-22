<?php

require __DIR__ . '/../../vendor/autoload.php';

use Paw\Core\Config;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

use Dotenv\Dotenv;

use Paw\Core\Router;
use Paw\Core\Request;

use Paw\Core\Database\ConnectionBuilder;

$dotenv = Dotenv::createUnsafeImmutable(__DIR__ . '/../../');
$dotenv->load();
$config = new Config;

$request = new Request;

$log = new Logger('pawprints-app');
$handler = new StreamHandler($config->get("LOG_PATH"));
$handler->setLevel($config->get("LOG_LEVEL"));
$log->pushHandler($handler);

$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->register();

$router=new Router;
$router->setLogger($log);
$router->get('/','PageController@index');
$router->get('/sobreNosotros', 'PageController@sobreNosotros');
$router->get('not_found', 'ErrorController@notFound');
$router->get('internal_error', 'ErrorController@internalError');
$router->get('/reserva', 'PageController@reserva');
$router->get('/catalogo', 'LibroController@catalogo');
$router->get('/detalle', 'LibroController@detalle');
$router->get('/catalogo/csv', 'LibroController@csv');
$router->get('/buscar', 'LibroController@buscar');

$connectionBuilder = new ConnectionBuilder;
$connectionBuilder->setLogger($log);
$connection = $connectionBuilder->make($config);

