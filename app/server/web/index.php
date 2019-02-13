<?php

require_once __DIR__ . '/../../vendor/autoload.php';

$container = new \Slim\Container(require_once __DIR__ . '/../app/config.php');

$services = require_once __DIR__ . '/../app/services.php';
foreach ($services as $service) {
    $container->register($service);
}

$container[\Slim\App::class]->run();
