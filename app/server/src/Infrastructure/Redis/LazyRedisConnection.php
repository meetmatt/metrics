<?php

namespace MeetMatt\Metrics\Server\Infrastructure\Redis;

use Redis;

class LazyRedisConnection implements RedisConnectionInterface
{
    /** @var string */
    private $host;

    /** @var int */
    private $port;

    /** @var float */
    private $timeout;

    public function __construct(string $host, int $port = 6379, float $timeout = 1)
    {
        $this->host = $host;
        $this->port = $port;
        $this->timeout = $timeout;
    }

    public function setex(string $key, int $ttl, string $value): bool
    {
        return $this->getConnection()->setex($key, $ttl, $value);
    }

    /**
     * @param string $key
     *
     * @return string|bool
     */
    public function get(string $key)
    {
        return $this->getConnection()->get($key);
    }

    public function expire(string $key, int $ttl): bool
    {
        return $this->getConnection()->expire($key, $ttl);
    }

    /**
     * @return Redis
     */
    protected function getConnection(): Redis
    {
        $redis = new Redis();
        $redis->connect($this->host, $this->port, $this->timeout);

        return $redis;
    }
}