<?php

namespace MeetMatt\Metrics\Server\Domain\Service;

use InvalidArgumentException;
use MeetMatt\Metrics\Server\Domain\Entity\Token;
use MeetMatt\Metrics\Server\Domain\Repository\TokenRepositoryInterface;
use MeetMatt\Metrics\Server\Domain\Repository\UserRepositoryInterface;
use MeetMatt\Metrics\Server\Domain\Service\Exception\UnauthorizedException;
use Ramsey\Uuid\Uuid;

class LoginService
{
    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * @var PasswordHashingServiceInterface
     */
    private $passwordHashingService;

    /**
     * @var TokenRepositoryInterface
     */
    private $tokenRepository;

    public function __construct(
        UserRepositoryInterface $userRepository,
        PasswordHashingServiceInterface $passwordHashingService,
        TokenRepositoryInterface $tokenRepository
    )
    {
        $this->userRepository = $userRepository;
        $this->passwordHashingService = $passwordHashingService;
        $this->tokenRepository = $tokenRepository;
    }

    /**
     * @param string $username
     * @param string $password
     *
     * @throws InvalidArgumentException
     * @throws UnauthorizedException
     *
     * @return Token
     */
    public function login(string $username, string $password): Token
    {
        $username = trim($username);
        if (strlen($username) < 6) {
            throw new InvalidArgumentException('Username must be at least 6 characters long');
        }

        $user = $this->userRepository->findByUsername($username);
        $knownHash = $user !== null ? $user->getPassword() : 'this is not a valid pass hash';

        if (!$this->passwordHashingService->isSamePasswordHash($knownHash, $password)) {
            throw new UnauthorizedException();
        }

        if (null === $user) {
            throw new UnauthorizedException();
        }

        /** @noinspection PhpUnhandledExceptionInspection */
        $id = Uuid::uuid4();
        $token = new Token($id, $user->getId());
        $this->tokenRepository->add($token);

        return $token;
    }
}