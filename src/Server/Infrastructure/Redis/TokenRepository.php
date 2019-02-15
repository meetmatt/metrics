<?php

namespace MeetMatt\Metrics\Server\Infrastructure\Redis;

use MeetMatt\Metrics\Server\Domain\Metrics\MetricsInterface;
use MeetMatt\Metrics\Server\Domain\User\Token;
use MeetMatt\Metrics\Server\Domain\User\TokenRepositoryInterface;

class TokenRepository implements TokenRepositoryInterface
{
    const TTL = 60;

    /** @var RedisConnectionInterface */
    private $redis;

    /** @var MetricsInterface */
	private $metrics;

	public function __construct(RedisConnectionInterface $redis, MetricsInterface $metrics)
    {
        $this->redis = $redis;
		$this->metrics = $metrics;
	}

    public function add(Token $token): void
    {
        $this->redis->setex($token->getId(), self::TTL, $token->getUserId());
        $this->metrics->increment('api.token.add');
    }

    public function find(string $id): ?Token
    {
		$this->metrics->increment('api.token.find');
        $userId = $this->redis->get($id);
        if (false === $userId) {
            return null;
        }

        return new Token($id, (int)$userId);
    }

    public function findAndRefresh(?string $id): ?Token
    {
		$this->metrics->increment('api.token.find_and_refresh');
        if (null === $id) {
            return null;
        }

        if (!$this->redis->expire($id, self::TTL)) {
            return null;
        }

        return $this->find($id);
    }
}