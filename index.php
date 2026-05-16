<?php
require 'vendor/autoload.php';

// Fat-Free Framework requires explicit inclusion (not PSR-4 autoloaded)
if (!class_exists('Base')) {
    require 'vendor/bcosca/fatfree-core/base.php';
}

// Load .env if it exists
if (file_exists(__DIR__ . '/.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
}

$f3 = \Base::instance();

// Database — from .env or fallback to hardcoded for legacy deployments
$f3->set('DB', new DB\SQL(
    'mysql:host=' . ($_ENV['DB_HOST'] ?? 'localhost') .
    ';port='      . ($_ENV['DB_PORT'] ?? '3306') .
    ';dbname='    . ($_ENV['DB_NAME'] ?? 'prayer_times'),
    $_ENV['DB_USER'] ?? 'root',
    $_ENV['DB_PASS'] ?? ''
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

// Default body class (TV view overrides this)
$f3->set('bodyClass', '');

$f3->run();
