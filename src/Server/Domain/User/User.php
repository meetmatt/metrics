<?php

namespace MeetMatt\Metrics\Server\Domain\User;

use InvalidArgumentException;
use LogicException;

final class User
{
    /** @var int */
    private $id;

    /** @var string */
    private $username;

    /** @var string */
    private $password;

    /**
     * @param string $username
     * @param string $password
     *
     * @throws InvalidArgumentException
     */
    public function __construct(string $username, string $password)
    {
        $username = trim($username);
        if (strlen($username) < 6) {
            throw new InvalidArgumentException('Username must be at least 6 characters long');
        }

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