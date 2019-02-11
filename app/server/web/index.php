<?php

use MeetMatt\Metrics\Server\Application\Container\ServiceProvider;
use Slim\App;
use Slim\Container;

require_once __DIR__ . '/../../vendor/autoload.php';

$container = new Container(require_once __DIR__ . '/../app/config.php');
$container->register(new ServiceProvider());

$slim = new App($container);
foreach (require_once __DIR__ . '/../app/routes.php' as $route) {
    $slim->map([$route['method']], $route['pattern'], $route['action']);
}

/** @noinspection PhpUnhandledExceptionInspection */
$slim->run();
