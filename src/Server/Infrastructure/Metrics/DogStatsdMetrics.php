<?php

namespace MeetMatt\Metrics\Server\Infrastructure\Metrics;

use DataDog\BatchedDogStatsd;
use MeetMatt\Metrics\Server\Domain\Metrics\MetricsInterface;

class DogStatsdMetrics implements MetricsInterface
{
    /** @var BatchedDogStatsd */
    private $dogStatsd;

    public function __construct(string $host, int $port = 8125, array $tags = [])
    {
        $this->dogStatsd = new RemoteUdpBatchedDogStatsd([
            'host'        => $host,
            'port'        => $port,
            'global_tags' => $tags,
        ]);
    }

    public function flush(): void
    {
        $this->dogStatsd->flushBuffer();
    }

    public function increment(string $metric, array $tags = null, int $value = 1): void
    {
        $this->dogStatsd->increment($metric, 1.0, $tags, $value);
    }

    public function decrement(string $metric, array $tags = null, int $value = -1): void
    {
        $this->dogStatsd->decrement($metric, 1.0, $tags, $value);
    }

    public function timing(string $metric, $timeMilliseconds, array $tags = null): void
    {
        $this->dogStatsd->timing($metric, $timeMilliseconds, 1.0, $tags);
    }

    public function microtiming(string $metric, $timeSeconds, array $tags = null): void
    {
        $this->dogStatsd->microtiming($metric, $timeSeconds * 1000, 1.0, $tags);
    }

    public function timer(string $metric, callable $callable, array $tags = null)
    {
        $startTime = microtime(true);
        $result    = $callable();
        $endTime   = microtime(true);
        $this->microtiming($metric, $endTime - $startTime, $tags);

        return $result;
    }

    public function gauge(string $metric, $value, array $tags = null): void
    {
        $this->dogStatsd->gauge($metric, $value, 1.0, $tags);
    }

    public function histogram(string $metric, $value, array $tags = null): void
    {
        $this->dogStatsd->histogram($metric, $value, 1.0, $tags);
    }

    public function distribution(string $metric, $value, array $tags = null): void
    {
        $this->dogStatsd->distribution($metric, $value, 1.0, $tags);
    }

    public function set(string $metric, $value, array $tags = null): void
    {
        $this->dogStatsd->set($metric, $value, 1.0, $tags);
    }
}