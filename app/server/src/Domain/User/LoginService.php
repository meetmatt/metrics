<?php

namespace MeetMatt\Metrics\Server\Domain\User;

use InvalidArgumentException;
use MeetMatt\Metrics\Server\Domain\Exception\UnauthorizedException;
use MeetMatt\Metrics\Server\Domain\Identity\RandomIdGeneratorInterface;

class LoginService
{
    /** @var UserRepositoryInterface */
    private $userRepository;

    /** @var PasswordHashingServiceInterface */
    private $passwordHashingService;

    /** @var RandomIdGeneratorInterface */
    private $randomIdGenerator;

    /** @var TokenRepositoryInterface */
    private $tokenRepository;

    public function __construct(
        UserRepositoryInterface $userRepository,
        PasswordHashingServiceInterface $passwordHashingService,
        RandomIdGeneratorInterface $randomIdGenerator,
        TokenRepositoryInterface $tokenRepository
    )
    {
        $this->userRepository = $userRepository;
        $this->passwordHashingService = $passwordHashingService;
        $this->randomIdGenerator = $randomIdGenerator;
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

        $token = new Token(
            $this->randomIdGenerator->generate(),
            $user->getId()
        );
        $this->tokenRepository->add($token);

        return $token;
    }
}