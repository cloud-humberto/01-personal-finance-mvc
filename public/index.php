<?php
declare(strict_types=1);

date_default_timezone_set('America/Sao_Paulo');

// Autoload PSR-4 simples para App\*
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $baseDir = __DIR__ . '/../src/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relativeClass = substr($class, $len);
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

    if (file_exists($file)) {
        require_once $file;
    }
});

use App\Core\Router;
use App\Core\Session;

Session::start();

$router = new Router();

// Rotas de Autenticação
$router->get('/login', 'AuthController@showLogin');
$router->post('/login', 'AuthController@login');
$router->get('/register', 'AuthController@showRegister');
$router->post('/register', 'AuthController@register');
$router->get('/logout', 'AuthController@logout');

// Rotas do Dashboard e Transações
$router->get('/', 'DashboardController@index');
$router->get('/dashboard', 'DashboardController@index');
$router->post('/transactions/create', 'TransactionController@store');
$router->post('/transactions/delete', 'TransactionController@delete');

$requestUri = $_SERVER['REQUEST_URI'] ?? '/';
$requestMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';

$router->dispatch($requestUri, $requestMethod);
