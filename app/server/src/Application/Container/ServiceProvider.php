<?php

namespace MeetMatt\Metrics\Server\Application\Container;

use MeetMatt\Metrics\Server\Domain\Repository\TaskListRepositoryInterface;
use MeetMatt\Metrics\Server\Domain\Repository\TokenRepositoryInterface;
use MeetMatt\Metrics\Server\Domain\Repository\UserRepositoryInterface;
use MeetMatt\Metrics\Server\Domain\Service\LoginService;
use MeetMatt\Metrics\Server\Domain\Service\PasswordHashingServiceInterface;
use MeetMatt\Metrics\Server\Domain\Service\RegistrationService;
use MeetMatt\Metrics\Server\Infrastructure\Cryptography\PasswordHashingService;
use MeetMatt\Metrics\Server\Infrastructure\Mysql\TaskListRepository;
use MeetMatt\Metrics\Server\Infrastructure\Redis\TokenRepository;
use MeetMatt\Metrics\Server\Infrastructure\Mysql\UserRepository;
use MeetMatt\Metrics\Server\Presentation\Action\TaskList\CreateAction;
use MeetMatt\Metrics\Server\Presentation\Action\TaskList\GetManyAction;
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

        $pimple[UserRepositoryInterface::class] = function (Container $container) {
            return new UserRepository($container[EasyDB::class]);
        };

        $pimple[TokenRepositoryInterface::class] = function (Container $container) {
            return new TokenRepository($container[Redis::class]);
        };

        $pimple[TaskListRepositoryInterface::class] = function (Container $container) {
            return new TaskListRepository($container[EasyDB::class]);
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

        $pimple[CreateAction::class] = function (Container $container) {
            return new CreateAction(
                $container[TokenRepositoryInterface::class],
                $container[TaskListRepositoryInterface::class]
            );
        };
    }
}