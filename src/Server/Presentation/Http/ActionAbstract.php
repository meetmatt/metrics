<?php

namespace MeetMatt\Metrics\Server\Presentation\Http;

use InvalidArgumentException;
use JsonException;
use MeetMatt\Metrics\Server\Domain\Exception\UnauthorizedException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;

abstract class ActionAbstract
{
    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param array                  $arguments
     *
     * @throws Throwable
     *
     * @return ResponseInterface
     */
    abstract public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $arguments = []
    ): ResponseInterface;

    /**
     * @noinspection PhpDocRedundantThrowsInspection
     *
     * @param ServerRequestInterface $request
     * @param array                  $requiredFields
     *
     * @return array|string|bool|null
     */
    protected function getJsonBody(ServerRequestInterface $request, array $requiredFields = [])
    {
        $body = json_decode($request->getBody(), true, 3, JSON_THROW_ON_ERROR);
        if (null === $body) {
            throw new InvalidArgumentException('Request json body is required');
        }
        foreach ($requiredFields as $field) {
            if (!isset($body[$field])) {
                throw new InvalidArgumentException($field . ' is required');
            }
        }

        return $body;
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

    /**
     * @param ServerRequestInterface $request
     *
     * @throws UnauthorizedException
     *
     * @return int
     */
    protected function getUserId(ServerRequestInterface $request): int
    {
        $userId = $request->getAttribute('user_id', null);
        if (null === $userId) {
            throw new UnauthorizedException();
        }

        return $userId;
    }
}