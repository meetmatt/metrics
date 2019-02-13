<?php

namespace MeetMatt\Metrics\Server\Domain\Identity;

interface RandomIdGeneratorInterface
{
    public function generate(): string;
}