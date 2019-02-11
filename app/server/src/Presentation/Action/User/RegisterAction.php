<?php

namespace MeetMatt\Metrics\Server\Presentation\Action\User;

use InvalidArgumentException;
use MeetMatt\Metrics\Server\Domain\Service\RegistrationService;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class RegisterAction extends ActionAbstract
{
    /** @var RegistrationService */
    private $registrationService;

    public function __construct(RegistrationService $registrationService)
    {
        $this->registrationService = $registrationService;
    }

    public function __invoke(
        RequestInterface $request,
        ResponseInterface $response,
        array $arguments = []
    ): ResponseInterface
    {
        $body = $this->getJsonBody($request);

        try {
            $this->registrationService->register($body['username'], $body['password']);
        } catch (InvalidArgumentException $exception) {
            return $this->badRequest($response, $exception);
        }

        return $response->withStatus(201);
    }
}