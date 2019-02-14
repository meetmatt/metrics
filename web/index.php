<?php

require_once __DIR__ . '/../vendor/autoload.php';

$container = new \Slim\Container(require_once __DIR__ . '/../app/server/config.php');

$services = require_once __DIR__ . '/../app/server/services.php';
foreach ($services as $service) {
    $container->register($service);
}

$container[\Slim\App::class]->run();
