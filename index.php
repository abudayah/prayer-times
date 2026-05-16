<?php
require 'vendor/autoload.php';

// Load .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
$dotenv->required(['DB_HOST', 'DB_NAME', 'DB_USER', 'DB_PASS', 'APP_BASE_PATH']);

$f3 = \Base::instance();

// Database
$f3->set('DB', new DB\SQL(
    'mysql:host=' . $_ENV['DB_HOST'] . ';port=' . ($_ENV['DB_PORT'] ?? '3306') . ';dbname=' . $_ENV['DB_NAME'],
    $_ENV['DB_USER'],
    $_ENV['DB_PASS']
));

// Before routing - protect admin routes
$f3->set('beforeroute', function($f3) {
    $route = $f3->get('PATTERN');
    if (in_array($route, ['/admin', '/upload', '/delete/@id', '/toggle-publish/@id']) && !$f3->get('SESSION.user')) {
        $f3->reroute('/login');
    }
});

$f3->config('src/configs/config.cfg');
$f3->config('src/configs/routes.cfg');

// Expose version to all templates
$f3->set('APP_VERSION', $_ENV['APP_VERSION'] ?? '1.0.0');

$f3->run();
