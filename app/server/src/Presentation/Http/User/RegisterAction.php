<?php

namespace MeetMatt\Metrics\Server\Presentation\Http\User;

use MeetMatt\Metrics\Server\Domain\User\RegistrationService;
use MeetMatt\Metrics\Server\Presentation\Http\ActionAbstract;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class RegisterAction extends ActionAbstract
{
    /** @var RegistrationService */
    private $registrationService;

    public function __construct(RegistrationService $registrationService)
    {
        $this->registrationService = $registrationService;
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $arguments = []
    ): ResponseInterface
    {
        $body = $this->getJsonBody($request, ['username', 'password']);

        $this->registrationService->register($body['username'], $body['password']);

        return $response->withStatus(201);
    }
}