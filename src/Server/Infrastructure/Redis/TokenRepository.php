<?php

namespace MeetMatt\Metrics\Server\Infrastructure\Redis;

use MeetMatt\Metrics\Server\Domain\User\Token;
use MeetMatt\Metrics\Server\Domain\User\TokenRepositoryInterface;

class TokenRepository implements TokenRepositoryInterface
{
    const TTL = 86400;

    /** @var RedisConnectionInterface */
    private $redis;

    public function __construct(RedisConnectionInterface $redis)
    {
        $this->redis = $redis;
    }

    public function add(Token $token): void
    {
        $this->redis->setex($token->getId(), self::TTL, $token->getUserId());
    }

    public function find(string $id): ?Token
    {
        $userId = $this->redis->get($id);
        if (false === $userId) {
            return null;
        }

        return new Token($id, (int)$userId);
    }

    public function findAndRefresh(?string $id): ?Token
    {
        if (null === $id) {
            return null;
        }

        if (!$this->redis->expire($id, self::TTL)) {
            return null;
        }

        return $this->find($id);
    }
}