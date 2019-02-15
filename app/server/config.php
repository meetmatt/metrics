<?php

return [
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
        'statsd' => [
            'host' => 'telegraf',
            'tags' => [
                'application' => 'todo_api',
            ],
        ],
    ],
    'routes' => require_once __DIR__ . '/routes.php',
];