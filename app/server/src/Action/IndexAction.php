<?php

namespace MeetMatt\Metrics\Server\Action;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class IndexAction
{
	public function __invoke(RequestInterface $request, ResponseInterface $response, array $arguments = [])
	{
		$response->getBody()->write('Hello world');

		return $response;
	}
}