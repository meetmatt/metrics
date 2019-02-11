<?php

use MeetMatt\Metrics\Server\Presentation\Action\TaskList\CreateTaskAction;
use MeetMatt\Metrics\Server\Presentation\Action\TaskList\CreateAction;
use MeetMatt\Metrics\Server\Presentation\Action\TaskList\DeleteAction;
use MeetMatt\Metrics\Server\Presentation\Action\TaskList\DeleteTaskAction;
use MeetMatt\Metrics\Server\Presentation\Action\TaskList\GetAction;
use MeetMatt\Metrics\Server\Presentation\Action\TaskList\GetManyAction;
use MeetMatt\Metrics\Server\Presentation\Action\TaskList\GetTasksAction;
use MeetMatt\Metrics\Server\Presentation\Action\TaskList\MarkTaskAction;
use MeetMatt\Metrics\Server\Presentation\Action\User\LoginAction;
use MeetMatt\Metrics\Server\Presentation\Action\User\RegisterAction;

return [
    [
        'method' => 'POST',
        'pattern' => '/user',
        'action' => RegisterAction::class,
    ],
    [
        'method' => 'POST',
        'pattern' => '/login',
        'action' => LoginAction::class,
    ],
    [
        'method' => 'POST',
        'pattern' => '/lists',
        'action' => CreateAction::class,
    ],
    [
        'method' => 'GET',
        'pattern' => '/lists',
        'action' => GetManyAction::class,
    ],
    [
        'method' => 'DELETE',
        'pattern' => '/lists/{id}',
        'action' => DeleteAction::class,
    ],
    [
        'method' => 'GET',
        'pattern' => '/lists/{id}',
        'action' => GetAction::class,
    ],
    [
        'method' => 'GET',
        'pattern' => '/lists/{id}/tasks',
        'action' => GetTasksAction::class,
    ],
    [
        'method' => 'POST',
        'pattern' => '/lists/{id}/tasks',
        'action' => CreateTaskAction::class,
    ],
    [
        'method' => 'PATCH',
        'pattern' => '/lists/{list_id}/tasks/{task_id}',
        'action' => MarkTaskAction::class,
    ],
    [
        'method' => 'DELETE',
        'pattern' => '/lists/{list_id}/tasks/{task_id}',
        'action' => DeleteTaskAction::class,
    ],
];