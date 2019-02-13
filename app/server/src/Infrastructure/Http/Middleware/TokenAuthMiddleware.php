<?php

namespace MeetMatt\Metrics\Server\Infrastructure\Http\Middleware;

use MeetMatt\Metrics\Server\Domain\User\TokenRepositoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class TokenAuthMiddleware
{
    /** @var TokenRepositoryInterface */
    private $tokenRepository;

    public function __construct(TokenRepositoryInterface $tokenRepository)
    {
        $this->tokenRepository = $tokenRepository;
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        callable $next
    ): ResponseInterface
    {
        $response = $next(
            $request->withAttribute('user_id', $this->getUserId($request)),
            $response
        );

        return $response;
    }

    protected function getUserId(ServerRequestInterface $request): ?int
    {
        $tokenId = $this->getAuthTokenId($request);
        if (null === $tokenId) {
            return null;
        }

        $token = $this->tokenRepository->findAndRefresh($tokenId);
        if (null === $token) {
            return null;
        }

        return $token->getUserId();
    }

    private function getAuthTokenId(ServerRequestInterface $request): ?string
    {
        if (!$request->hasHeader('Authorization')) {
            return null;
        }

        $header = $request->getHeader('Authorization');
        $headerParts = explode(' ', $header[0], 2);
        if (count($headerParts) < 2) {
            return null;
        }

        return $headerParts[1];
    }
}