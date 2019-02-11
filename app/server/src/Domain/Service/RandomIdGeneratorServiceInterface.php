<?php

namespace MeetMatt\Metrics\Server\Domain\Service;

interface RandomIdGeneratorServiceInterface
{
    public function generate(): string;
}