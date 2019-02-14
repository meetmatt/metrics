<?php

namespace MeetMatt\Metrics\Server\Infrastructure\Container;

use MeetMatt\Metrics\Server\Domain\Task\TaskService;
use MeetMatt\Metrics\Server\Domain\User\LoginService;
use MeetMatt\Metrics\Server\Domain\User\RegistrationService;
use MeetMatt\Metrics\Server\Domain\TaskList\TaskListService;
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
use Pimple\ServiceProviderInterface;
use Pimple\Container;

class PresentationServiceProvider implements ServiceProviderInterface
{
    public function register(Container $pimple)
    {
        $pimple[RegisterAction::class] = function (Container $container) {
            return new RegisterAction($container[RegistrationService::class]);
        };

        $pimple[LoginAction::class] = function (Container $container) {
            return new LoginAction($container[LoginService::class]);
        };

        $pimple[CreateAction::class] = function (Container $container) {
            return new CreateAction($container[TaskListService::class]);
        };

        $pimple[GetManyAction::class] = function (Container $container) {
            return new GetManyAction($container[TaskListService::class]);
        };

        $pimple[GetAction::class] = function (Container $container) {
            return new GetAction($container[TaskListService::class]);
        };

        $pimple[DeleteAction::class] = function (Container $container) {
            return new DeleteAction($container[TaskListService::class]);
        };

        $pimple[GetTasksAction::class] = function (Container $container) {
            return new GetTasksAction($container[TaskService::class]);
        };

        $pimple[CreateTaskAction::class] = function (Container $container) {
            return new CreateTaskAction($container[TaskService::class]);
        };

        $pimple[MarkTaskAction::class] = function (Container $container) {
            return new MarkTaskAction($container[TaskService::class]);
        };

        $pimple[DeleteTaskAction::class] = function (Container $container) {
            return new DeleteTaskAction($container[TaskService::class]);
        };
    }
}