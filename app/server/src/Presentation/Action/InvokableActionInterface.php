<?php

namespace MeetMatt\Metrics\Server\Presentation\Action;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

interface InvokableActionInterface
{
    /**
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @param array $arguments
     *
     * @return ResponseInterface
     */
    public function __invoke(
        RequestInterface $request,
        ResponseInterface $response,
        array $arguments = []
    ): ResponseInterface;
}