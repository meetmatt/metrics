<?php

namespace MeetMatt\Metrics\Server\Application\Container;

use MeetMatt\Metrics\Server\Domain\Repository\TaskListRepositoryInterface;
use MeetMatt\Metrics\Server\Domain\Repository\TaskRepositoryInterface;
use MeetMatt\Metrics\Server\Domain\Repository\TokenRepositoryInterface;
use MeetMatt\Metrics\Server\Domain\Repository\UserRepositoryInterface;
use MeetMatt\Metrics\Server\Domain\Service\LoginService;
use MeetMatt\Metrics\Server\Domain\Service\PasswordHashingServiceInterface;
use MeetMatt\Metrics\Server\Domain\Service\RandomIdGeneratorServiceInterface;
use MeetMatt\Metrics\Server\Domain\Service\RegistrationService;
use MeetMatt\Metrics\Server\Domain\Service\TaskListService;
use MeetMatt\Metrics\Server\Infrastructure\Cryptography\PasswordHashingService;
use MeetMatt\Metrics\Server\Infrastructure\Cryptography\RandomIdGeneratorService;
use MeetMatt\Metrics\Server\Infrastructure\Mysql\TaskListRepository;
use MeetMatt\Metrics\Server\Infrastructure\Mysql\TaskRepository;
use MeetMatt\Metrics\Server\Infrastructure\Redis\TokenRepository;
use MeetMatt\Metrics\Server\Infrastructure\Mysql\UserRepository;
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
use ParagonIE\EasyDB\EasyDB;
use ParagonIE\EasyDB\Factory;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Redis;

class ServiceProvider implements ServiceProviderInterface
{
    public function register(Container $pimple)
    {
        $pimple[EasyDB::class] = function (Container $container) {
            $settings = $container['settings']['mysql'];

            return Factory::create(
                sprintf(
                    'mysql:host=%s;dbname=%s',
                    $settings['host'],
                    $settings['database']
                ),
                $settings['user'],
                $settings['password'] ?? null
            );
        };

        $pimple[Redis::class] = function (Container $container) {
            $redis = new Redis();
            $redis->connect(
                $container['settings']['redis']['host'],
                $container['settings']['redis']['port'] ?? 6379
            );

            return $redis;
        };

        $pimple[PasswordHashingServiceInterface::class] = function () {
            return new PasswordHashingService();
        };

        $pimple[RandomIdGeneratorServiceInterface::class] = function () {
            return new RandomIdGeneratorService();
        };

        $pimple[UserRepositoryInterface::class] = function (Container $container) {
            return new UserRepository($container[EasyDB::class]);
        };

        $pimple[TokenRepositoryInterface::class] = function (Container $container) {
            return new TokenRepository($container[Redis::class]);
        };

        $pimple[TaskListRepositoryInterface::class] = function (Container $container) {
            return new TaskListRepository($container[EasyDB::class]);
        };

        $pimple[TaskRepositoryInterface::class] = function (Container $container) {
            return new TaskRepository($container[EasyDB::class]);
        };

        $pimple[TaskListService::class] = function (Container $container) {
            return new TaskListService(
                $container[RandomIdGeneratorServiceInterface::class],
                $container[TaskListRepositoryInterface::class]
            );
        };

        $pimple[RegisterAction::class] = function (Container $container) {
            return new RegisterAction(
                new RegistrationService(
                    $container[PasswordHashingServiceInterface::class],
                    $container[UserRepositoryInterface::class]
                )
            );
        };

        $pimple[LoginAction::class] = function (Container $container) {
            return new LoginAction(
                new LoginService(
                    $container[UserRepositoryInterface::class],
                    $container[PasswordHashingServiceInterface::class],
                    $container[TokenRepositoryInterface::class]
                )
            );
        };

        $pimple[GetManyAction::class] = function (Container $container) {
            return new GetManyAction(
                $container[TokenRepositoryInterface::class],
                $container[TaskListRepositoryInterface::class]
            );
        };

        $pimple[GetAction::class] = function (Container $container) {
            return new GetAction(
                $container[TokenRepositoryInterface::class],
                $container[TaskListRepositoryInterface::class]
            );
        };

        $pimple[CreateAction::class] = function (Container $container) {
            return new CreateAction(
                $container[TokenRepositoryInterface::class],
                $container[TaskListService::class]
            );
        };

        $pimple[DeleteAction::class] = function (Container $container) {
            return new DeleteAction(
                $container[TokenRepositoryInterface::class],
                $container[TaskListService::class]
            );
        };

        $pimple[GetTasksAction::class] = function (Container $container) {
            return new GetTasksAction(
                $container[TokenRepositoryInterface::class],
                $container[TaskListRepositoryInterface::class],
                $container[TaskRepositoryInterface::class]
            );
        };

        $pimple[CreateTaskAction::class] = function (Container $container) {
            return new CreateTaskAction(
                $container[TokenRepositoryInterface::class],
                $container[TaskListRepositoryInterface::class],
                $container[RandomIdGeneratorServiceInterface::class],
                $container[TaskRepositoryInterface::class]
            );
        };

        $pimple[MarkTaskAction::class] = function (Container $container) {
            return new MarkTaskAction(
                $container[TokenRepositoryInterface::class],
                $container[TaskListRepositoryInterface::class],
                $container[TaskRepositoryInterface::class]
            );
        };

        $pimple[DeleteTaskAction::class] = function (Container $container) {
            return new DeleteTaskAction(
                $container[TokenRepositoryInterface::class],
                $container[TaskListRepositoryInterface::class],
                $container[TaskRepositoryInterface::class]
            );
        };
    }
}