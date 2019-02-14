<?php

namespace MeetMatt\Metrics\Server\Infrastructure\Container;

use MeetMatt\Metrics\Server\Domain\Identity\RandomIdGeneratorInterface;
use MeetMatt\Metrics\Server\Domain\Task\TaskRepositoryInterface;
use MeetMatt\Metrics\Server\Domain\Task\TaskService;
use MeetMatt\Metrics\Server\Domain\TaskList\TaskListRepositoryInterface;
use MeetMatt\Metrics\Server\Domain\TaskList\TaskListService;
use MeetMatt\Metrics\Server\Domain\User\LoginService;
use MeetMatt\Metrics\Server\Domain\User\PasswordHashingServiceInterface;
use MeetMatt\Metrics\Server\Domain\User\RegistrationService;
use MeetMatt\Metrics\Server\Domain\User\TokenRepositoryInterface;
use MeetMatt\Metrics\Server\Domain\User\UserRepositoryInterface;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class DomainServiceProvider implements ServiceProviderInterface
{
    public function register(Container $pimple)
    {
        $pimple[RegistrationService::class] = function (Container $container) {
            return new RegistrationService(
                $container[PasswordHashingServiceInterface::class],
                $container[UserRepositoryInterface::class]
            );
        };

        $pimple[LoginService::class] = function (Container $container) {
            return new LoginService(
                $container[UserRepositoryInterface::class],
                $container[PasswordHashingServiceInterface::class],
                $container[RandomIdGeneratorInterface::class],
                $container[TokenRepositoryInterface::class]
            );
        };

        $pimple[TaskListService::class] = function (Container $container) {
            return new TaskListService(
                $container[RandomIdGeneratorInterface::class],
                $container[TaskListRepositoryInterface::class]
            );
        };

        $pimple[TaskService::class] = function (Container $container) {
            return new TaskService(
                $container[TaskListService::class],
                $container[RandomIdGeneratorInterface::class],
                $container[TaskRepositoryInterface::class]
            );
        };
    }
}