<?php

use MeetMatt\Metrics\Server\Infrastructure\Container\DomainServiceProvider;
use MeetMatt\Metrics\Server\Infrastructure\Container\InfrastructureServiceProvider;
use MeetMatt\Metrics\Server\Infrastructure\Container\PresentationServiceProvider;

return [
    new InfrastructureServiceProvider(),
    new DomainServiceProvider(),
    new PresentationServiceProvider(),
];