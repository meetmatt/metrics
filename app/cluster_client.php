#!/usr/bin/env php
<?php

use MeetMatt\Metrics\Server\Infrastructure\Metrics\DogStatsdMetrics;

if ($argc < 4) {
    echo <<<DOC
        Usage: ${argv[0]} tag telegraf sleep
        
        tag         Application tag
        telegraf    IP address of Telegraf
        sleep       Microseconds to sleep between measurements

        DOC;

    exit(1);
}

$tag      = $argv[1];
$telegraf = $argv[2];
$sleep    = isset($argv[3]) ? $argv[3] : 1000000;

echo "Sending metrics to {$telegraf} every ${sleep} microseconds with tag `application: ${tag}`\n";

require_once __DIR__ . '/../vendor/autoload.php';

$metrics = new DogStatsdMetrics($telegraf, 8125, ['application' => $tag]);

$i = 0;
while (true) {
    $metrics->increment('meetmatt.client.loop.count');
    echo '.';
    $i++;
    if ($i === 42) {
        echo "\n";
        $i = 0;
    }
    usleep($sleep);
}