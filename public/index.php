<?php
require __DIR__ . '/../vendor/autoload.php';

use App\Controllers\ShowController;
use App\Controllers\HomeController;
use App\Controllers\BookingController;
use App\Support\Env;
use App\Support\Response;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->safeLoad();
$dotenv->required(['APP_NAME', 'APP_ENV', 'APP_DEBUG', 'APP_URL', 'ORGANIZER_NAME', 'MAX_TICKETS_PER_BOOKING']);

error_reporting(E_ALL);
if (Env::get('APP_ENV', 'prod') === 'dev' && Env::bool('APP_DEBUG', false)) {
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
} else {
    ini_set('display_errors', '0');
    ini_set('display_startup_errors', '0');
    ini_set('log_errors', '1');
    ini_set('error_log', dirname(__DIR__) . '/storage/logs/php-error.log');
}

$config = require dirname(__DIR__) . '/config/app.php';
$shows = require dirname(__DIR__) . '/src/Data/shows.php';

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);

// Router
if ($path === '/' && $method === 'GET') {
    $controller = new HomeController();
    $data = $controller->index($config, $shows);
    require dirname(__DIR__) . '/views/home.php';
    exit;
}

if ($path === '/shows') {
    if ($method === 'GET') {
        (new ShowController())->index($shows);
    } elseif ($method === 'HEAD') {
        (new ShowController())->head();
    } else {
        Response::json(405, ['error' => 'Method Not Allowed'], ['Allow' => 'GET, HEAD']);
    }
}

if ($path === '/bookings') {
    if ($method === 'POST') {
        (new BookingController())->store($shows, $config);
    } elseif ($method === 'OPTIONS') {
        (new BookingController())->options();
    } else {
        Response::json(405, ['error' => 'Method Not Allowed'], ['Allow' => 'POST, OPTIONS']);
    }
}

Response::json(404, ['error' => 'Not Found']);