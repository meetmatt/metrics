<?php

namespace MeetMatt\Metrics\Server\Presentation\Http\User;

use MeetMatt\Metrics\Server\Domain\User\LoginService;
use MeetMatt\Metrics\Server\Presentation\Http\ActionAbstract;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class LoginAction extends ActionAbstract
{
    /** @var LoginService */
    private $loginService;

    public function __construct(LoginService $loginService)
    {
        $this->loginService = $loginService;
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $arguments = []
    ): ResponseInterface
    {
        $body = $this->getJsonBody($request, ['username', 'password']);

        $token = $this->loginService->login($body['username'], $body['password']);

        return $this->withJson($response, ['token' => $token->getId()]);
    }
}