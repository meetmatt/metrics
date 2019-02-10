<?php

namespace MeetMatt\Metrics\Server\Presentation\Action\User;

use JsonException;
use MeetMatt\Metrics\Server\Domain\Entity\User;
use MeetMatt\Metrics\Server\Domain\Repository\TokenRepositoryInterface;
use MeetMatt\Metrics\Server\Presentation\Action\InvokableActionInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

abstract class ActionAbstract implements InvokableActionInterface
{
    /**
     * @noinspection PhpDocRedundantThrowsInspection
     *
     * @param RequestInterface $request
     *
     * @throws JsonException
     *
     * @return array|string|bool|null
     */
    protected function getJsonBody(RequestInterface $request)
    {
        return json_decode($request->getBody(), true, 3, JSON_THROW_ON_ERROR);
    }

    /**
     * @noinspection PhpDocRedundantThrowsInspection
     *
     * @param ResponseInterface $response
     * @param mixed             $data
     *
     * @throws JsonException
     *
     * @return ResponseInterface
     */
    protected function withJson(ResponseInterface $response, $data): ResponseInterface
    {
        $body = $response->getBody();
        $body->rewind();
        $body->write(json_encode($data, JSON_THROW_ON_ERROR, 3));

        return $response;
    }

    protected function getAuthTokenId(RequestInterface $request): ?string
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

    protected function unauthorized(ResponseInterface $response): ResponseInterface
    {
        return $response->withStatus(401);
    }

    protected function getUserId(TokenRepositoryInterface $tokenRepository, RequestInterface $request): ?int
    {
        $tokenId = $this->getAuthTokenId($request);
        $token = $tokenRepository->findAndRefresh($tokenId);
        if (null === $token) {
            return null;
        }

        return $token->getUserId();
    }
}