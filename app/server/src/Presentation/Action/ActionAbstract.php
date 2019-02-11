<?php

namespace MeetMatt\Metrics\Server\Presentation\Action\User;

use JsonException;
use MeetMatt\Metrics\Server\Domain\Repository\TokenRepositoryInterface;
use MeetMatt\Metrics\Server\Presentation\Action\InvokableActionInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Throwable;

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
     * @param int               $status
     *
     * @throws JsonException
     *
     * @return ResponseInterface
     */
    protected function withJson(ResponseInterface $response, $data, $status = 200): ResponseInterface
    {
        $body = $response->getBody();
        $body->rewind();
        $body->write(json_encode($data, JSON_THROW_ON_ERROR, 3));

        return $response
            ->withStatus($status)
            ->withHeader('Content-type', 'application/json');
    }

    protected function badRequest(ResponseInterface $response, Throwable $exception): ResponseInterface
    {
        return $response->withStatus(400, $exception->getMessage());
    }

    protected function unauthorized(ResponseInterface $response): ResponseInterface
    {
        return $response->withStatus(401);
    }

    protected function accessDenied(ResponseInterface $response): ResponseInterface
    {
        return $response->withStatus(403);
    }

    protected function notFound(ResponseInterface $response): ResponseInterface
    {
        return $response->withStatus(404);
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

    private function getAuthTokenId(RequestInterface $request): ?string
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