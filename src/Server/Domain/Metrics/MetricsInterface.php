<?php

namespace MeetMatt\Metrics\Server\Domain\Metrics;

interface MetricsInterface
{
    /**
     * Sends buffered metrics to Statsd
     */
    public function flush(): void;

    /**
     * Increments a stats counter.
     *
     * @param string $metric
     * @param array  $tags
     * @param int    $value
     */
    public function increment(string $metric, array $tags = null, int $value = 1): void;

    /**
     * Decrements a stats counter.
     *
     * @param string $metric
     * @param array  $tags
     * @param int    $value
     **/
    public function decrement(string $metric, array $tags = null, int $value = -1): void;

    /**
     * Log timing information
     *
     * @param string    $metric
     * @param int|float $timeMilliseconds
     * @param array     $tags
     */
    public function timing(string $metric, $timeMilliseconds, array $tags = null): void;

    /**
     * Alias for the timing function when used with micro-timing
     * e.g. convenient when used with microtime(true) timers
     *
     * @param string    $metric
     * @param int|float $timeSeconds
     * @param array     $tags
     **/
    public function microtiming(string $metric, $timeSeconds, array $tags = null): void;

    /**
     * Gauge
     *
     * @param string    $metric
     * @param int|float $value
     * @param array     $tags
     **/
    public function gauge(string $metric, $value, array $tags = null): void;

    /**
     * Histogram
     *
     * @param string    $metric
     * @param int|float $value
     * @param array     $tags
     **/
    public function histogram(string $metric, $value, array $tags = null): void;

    /**
     * Distribution
     *
     * @param string    $metric
     * @param int|float $value
     * @param array     $tags
     **/
    public function distribution(string $metric, $value, array $tags = null): void;

    /**
     * Set
     *
     * @param string    $metric
     * @param int|float $value
     * @param array     $tags
     **/
    public function set(string $metric, $value, array $tags = null): void;
}