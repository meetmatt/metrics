<?php

use MeetMatt\Metrics\Server\Domain\Metrics\MetricsInterface;
use Slim\App;
use Slim\Container;

$timerStart = microtime(true);

require_once __DIR__ . '/../vendor/autoload.php';

$container = new Container(require_once __DIR__ . '/../app/server/config.php');

$services = require_once __DIR__ . '/../app/server/services.php';
foreach ($services as $service) {
    $container->register($service);
}

/** @var App $app */
$app = $container[App::class];
$app->run();

// after response
$timerEnd = microtime(true);

/** @var MetricsInterface $metrics */
$metrics = $container[MetricsInterface::class];
$metrics->increment('api.calls');
// for some reason results in influx are in microseconds
$metrics->microtiming('api.response_time', $timerEnd - $timerStart);
$metrics->flush();

