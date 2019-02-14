<?php

namespace MeetMatt\Metrics\Server\Infrastructure\Redis;

interface RedisConnectionInterface
{
    public function setex(string $key, int $ttl, string $value): bool;

    /**
     * @param string $key
     *
     * @return string|bool
     */
    public function get(string $key);

    public function expire(string $key, int $ttl): bool;
}