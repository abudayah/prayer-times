<?php
require 'vendor/autoload.php';

$f3 = \Base::instance();

// Database Configuration
$f3->set('DB', new DB\SQL(
    'mysql:host=localhost;port=3306;dbname=prayer_times',
    'root',
    ''
));

// Before routing - protect admin routes
$f3->set('beforeroute', function($f3) {
    $route = $f3->get('PATTERN');

    if (in_array($route, ['/admin', '/upload', '/delete/@id']) && !$f3->get('SESSION.user')) {
        $f3->reroute('/login');
    }
});

$f3->config('src/configs/config.cfg');
$f3->config('src/configs/routes.cfg');

$f3->run();
