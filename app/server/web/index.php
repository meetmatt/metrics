<?php

use MeetMatt\Metrics\Server\Action\IndexAction;
use ParagonIE\EasyDB\Factory;

require_once __DIR__ . '/../../vendor/autoload.php';

$slim = new \Slim\App([
	'db' => function () {
		return Factory::create('mysql:host=mysql;dbname=todo', 'root');
	},
	'settings' => [
		'displayErrorDetails' => true,
	],
]);

$slim->get('/', IndexAction::class);
$slim->run();
