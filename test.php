<?php declare(strict_types=1);

require_once 'vendor/autoload.php';

use DataDog\DogStatsd;
use MeetMatt\Metrics\Server\Infrastructure\Metrics\DogStatsdMetrics;

$statsd = new DogStatsdMetrics('telegraf');

$statsd->gauge('test.gauge', 1);
$statsd->increment('test.increment');
$statsd->flush();

/*$statsd = new DogStatsd(['host' => 'localhost', 'port' => 8125]);
$statsd->gauge('test.gauge', 1);
$statsd->increment('test.increment');*/

$statsd = new DogStatsd(['host' => 'telegraf']);
$statsd->gauge('native.test.gauge', 1);
$statsd->increment('native.test.increment');


var_dump(error_get_last());
