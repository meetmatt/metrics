<?php

use MeetMatt\Metrics\Server\Application\Container\ServiceProvider;
use MeetMatt\Metrics\Server\Presentation\Action\TaskList\CreateAction;
use MeetMatt\Metrics\Server\Presentation\Action\TaskList\GetManyAction;
use MeetMatt\Metrics\Server\Presentation\Action\User\LoginAction;
use MeetMatt\Metrics\Server\Presentation\Action\User\RegisterAction;
use Slim\Container;

require_once __DIR__ . '/../../vendor/autoload.php';

$container = new Container([
    'settings' => [
        'displayErrorDetails' => true,
        'mysql' => [
            'host' => 'mysql',
            'database' => 'todo',
            'user' => 'root',
        ],
        'redis' => [
            'host' => 'redis',
        ],
    ],
]);
$container->register(new ServiceProvider());

$slim = new \Slim\App($container);

$slim->post('/user', RegisterAction::class);
$slim->post('/login', LoginAction::class);
$slim->get('/lists', GetManyAction::class);
$slim->post('/lists', CreateAction::class);

/** @noinspection PhpUnhandledExceptionInspection */
$slim->run();
