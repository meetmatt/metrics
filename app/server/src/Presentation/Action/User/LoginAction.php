<?php

namespace MeetMatt\Metrics\Server\Presentation\Action\User;

use InvalidArgumentException;
use MeetMatt\Metrics\Server\Domain\Service\Exception\UnauthorizedException;
use MeetMatt\Metrics\Server\Domain\Service\LoginService;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class LoginAction extends ActionAbstract
{
    /** @var LoginService */
    private $loginService;

    public function __construct(LoginService $loginService)
    {
        $this->loginService = $loginService;
    }

    public function __invoke(
        RequestInterface $request,
        ResponseInterface $response,
        array $arguments = []
    ): ResponseInterface
    {
        $body = $this->getJsonBody($request);

        try {
            $token = $this->loginService->login($body['username'], $body['password']);
        } catch (InvalidArgumentException $exception) {
            return $this->badRequest($response, $exception);
        } catch (UnauthorizedException $exception) {
            return $this->unauthorized($response);
        }

        return $this->withJson($response, ['token' => $token->getId()]);
    }
}