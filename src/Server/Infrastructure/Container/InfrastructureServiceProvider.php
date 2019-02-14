<?php

namespace MeetMatt\Metrics\Server\Infrastructure\Container;

use MeetMatt\Metrics\Server\Domain\Identity\RandomIdGeneratorInterface;
use MeetMatt\Metrics\Server\Domain\Task\TaskRepositoryInterface;
use MeetMatt\Metrics\Server\Domain\TaskList\TaskListRepositoryInterface;
use MeetMatt\Metrics\Server\Domain\User\PasswordHashingServiceInterface;
use MeetMatt\Metrics\Server\Domain\User\TokenRepositoryInterface;
use MeetMatt\Metrics\Server\Domain\User\UserRepositoryInterface;
use MeetMatt\Metrics\Server\Infrastructure\Cryptography\PasswordHashingService;
use MeetMatt\Metrics\Server\Infrastructure\Cryptography\RandomIdGenerator;
use MeetMatt\Metrics\Server\Infrastructure\Http\Middleware\ErrorResponseMiddleware;
use MeetMatt\Metrics\Server\Infrastructure\Http\Middleware\TokenAuthMiddleware;
use MeetMatt\Metrics\Server\Infrastructure\Mysql\TaskListRepository;
use MeetMatt\Metrics\Server\Infrastructure\Mysql\TaskRepository;
use MeetMatt\Metrics\Server\Infrastructure\Mysql\UserRepository;
use MeetMatt\Metrics\Server\Infrastructure\Redis\LazyRedisConnection;
use MeetMatt\Metrics\Server\Infrastructure\Redis\RedisConnectionInterface;
use MeetMatt\Metrics\Server\Infrastructure\Redis\TokenRepository;
use ParagonIE\EasyDB\EasyDB;
use ParagonIE\EasyDB\Factory;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Slim\App;

class InfrastructureServiceProvider implements ServiceProviderInterface
{
    public function register(Container $pimple)
    {
        $pimple[App::class] = function (Container $container) {
            $slim = new App($container);

            $slim->add($container[TokenAuthMiddleware::class]);

            foreach ($container['routes'] as $route) {
                $slim->map([$route['method']], $route['pattern'], $route['action']);
            }

            $slim->add($container[ErrorResponseMiddleware::class]);

            return $slim;
        };

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

        $pimple[RedisConnectionInterface::class] = function (Container $container) {
            return new LazyRedisConnection(
                $container['settings']['redis']['host'],
                $container['settings']['redis']['port'] ?? 6379
            );
        };

        $pimple[TokenAuthMiddleware::class] = function (Container $container) {
            return new TokenAuthMiddleware($container[TokenRepositoryInterface::class]);
        };

        $pimple[ErrorResponseMiddleware::class] = function () {
            return new ErrorResponseMiddleware();
        };

        $pimple[RandomIdGeneratorInterface::class] = function () {
            return new RandomIdGenerator();
        };

        $pimple[PasswordHashingServiceInterface::class] = function () {
            return new PasswordHashingService();
        };

        $pimple[UserRepositoryInterface::class] = function (Container $container) {
            return new UserRepository($container[EasyDB::class]);
        };

        $pimple[TokenRepositoryInterface::class] = function (Container $container) {
            return new TokenRepository($container[RedisConnectionInterface::class]);
        };

        $pimple[TaskListRepositoryInterface::class] = function (Container $container) {
            return new TaskListRepository($container[EasyDB::class]);
        };

        $pimple[TaskRepositoryInterface::class] = function (Container $container) {
            return new TaskRepository($container[EasyDB::class]);
        };
    }
}