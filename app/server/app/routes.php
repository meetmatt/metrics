<?php

use MeetMatt\Metrics\Server\Presentation\Http\TaskList\CreateTaskAction;
use MeetMatt\Metrics\Server\Presentation\Http\TaskList\CreateAction;
use MeetMatt\Metrics\Server\Presentation\Http\TaskList\DeleteAction;
use MeetMatt\Metrics\Server\Presentation\Http\TaskList\DeleteTaskAction;
use MeetMatt\Metrics\Server\Presentation\Http\TaskList\GetAction;
use MeetMatt\Metrics\Server\Presentation\Http\TaskList\GetManyAction;
use MeetMatt\Metrics\Server\Presentation\Http\TaskList\GetTasksAction;
use MeetMatt\Metrics\Server\Presentation\Http\TaskList\MarkTaskAction;
use MeetMatt\Metrics\Server\Presentation\Http\User\LoginAction;
use MeetMatt\Metrics\Server\Presentation\Http\User\RegisterAction;

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