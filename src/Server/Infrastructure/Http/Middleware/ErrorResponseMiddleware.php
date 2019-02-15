<?php

namespace MeetMatt\Metrics\Server\Infrastructure\Http\Middleware;

use InvalidArgumentException;
use JsonException;
use MeetMatt\Metrics\Server\Domain\Exception\AccessDeniedException;
use MeetMatt\Metrics\Server\Domain\Exception\NotFoundException;
use MeetMatt\Metrics\Server\Domain\Exception\UnauthorizedException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;

class ErrorResponseMiddleware
{
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        callable $next
    ): ResponseInterface
    {
        try {
            $response = $next($request, $response);
        } catch (InvalidArgumentException $exception) {
            return $response->withStatus(400, $exception->getMessage());
        } catch (UnauthorizedException $exception) {
            return $response->withStatus(401, $exception->getMessage());
        } catch (JsonException $exception) {
            return $response->withStatus(400, $exception->getMessage());
        } catch (AccessDeniedException $exception) {
            return $response->withStatus(403, $exception->getMessage());
        } catch (NotFoundException $exception) {
            return $response->withStatus(404, $exception->getMessage());
        } catch (Throwable $exception) {
        	return $response->withStatus(500, $exception->getMessage());
		}

        return $response;
    }
}