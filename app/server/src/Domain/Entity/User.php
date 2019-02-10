<?php

namespace MeetMatt\Metrics\Server\Domain\Entity;

use LogicException;

final class User
{
    /** @var int */
    private $id;

    /** @var string */
    private $username;

    /** @var string */
    private $password;

    public function __construct(string $username, string $password)
    {
        $this->username = $username;
        $this->password = $password;
    }

    public function setId(string $id)
    {
        if (null !== $this->id) {
            throw new LogicException('Cannot set id to user more than once');
        }
        $this->id = $id;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}