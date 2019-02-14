<?php

use MeetMatt\Metrics\Client\TokenExpiredException;
use MeetMatt\Metrics\Client\Api;

require_once __DIR__ . '/../src/Client/TokenExpiredException.php';
require_once __DIR__ . '/../src/Client/Response.php';
require_once __DIR__ . '/../src/Client/Curl.php';
require_once __DIR__ . '/../src/Client/Api.php';

function choose($probability)
{
    return mt_rand(1, (int)round(1 / $probability)) === 1;
}

function writeLog($message)
{
    echo '[' . date('Y-m-d H:i:s.') . '] ' . $message . PHP_EOL;
}

function randomString()
{
    return md5(microtime(true) . mt_rand(1000, 9999));
}

$randomString = randomString();
$username = 'user_' . substr($randomString, mt_rand(0, 15), 6);
$password = substr($randomString, 15, 16);

$isRegistered = false;
$token = null;
$listId = null;
$taskId = null;

$api = new Api('http://nginx');

while (true) {
    usleep(mt_rand(1, 5) * 100000);

    if (choose(0.01)) {
        writeLog('Forgot token');
        $token = null;
        continue;
    }

    if (!empty($listId) && choose(0.04)) {
        // force choose a list or create a list
        $listId = null;
        $taskId = null;
        writeLog('Returned to start');
        continue;
    }

    try {
        if (empty($token)) {
            if ($isRegistered) {
                $token = $api->login($username, $password);
                writeLog('Logged in');
                continue;
            } else {
                $api->register($username, $password);
                writeLog('Registered');
                $isRegistered = true;
                continue;
            }
        }

        if (empty($listId)) {
            $lists = $api->getLists($token);
            if (empty($lists) || choose(0.1)) {
                $api->createList($token, 'To do list #' . randomString());
                writeLog('Created list');
            } else {
                if (count($lists) === 1) {
                    $listId = $lists[0]['id'];
                } else {
                    $listId = $lists[mt_rand(0, count($lists) - 1)]['id'];
                }
                writeLog('Chose from ' . count($lists) . ' lists');
            }
            continue;
        }

        if (!empty($taskId) && choose(0.1)) {
            // force choose another task or create a new task
            writeLog('Back to list');
            $taskId = null;
        }

        if (empty($taskId)) {
            $tasks = $api->getTasks($token, $listId);
            if (empty($tasks) || choose(0.3)) {
                $api->createTask($token, $listId, 'Task #' . randomString());
                writeLog('Created task');
            } else {
                if (count($tasks) === 1) {
                    $taskId = $tasks[0]['id'];
                } else {
                    $taskId = $tasks[mt_rand(0, count($tasks) - 1)]['id'];
                }
                writeLog('Chose from ' . count($tasks) . ' tasks');
            }
            continue;
        }

        if (choose(0.3)) {
            $api->markTask($token, $listId, $taskId, true);
            writeLog('Marked task as done');
            $taskId = null;
            continue;
        }

        if (choose(0.1)) {
            $api->markTask($token, $listId, $taskId, false);
            writeLog('Marked task as undone');
            $taskId = null;
            continue;
        }

        if (choose(0.1)) {
            $api->deleteTask($token, $listId, $taskId);
            writeLog('Deleted task');
            $taskId = null;
            continue;
        }

        if (choose(0.05)) {
            $api->deleteList($token, $listId);
            writeLog('Deleted list');
            $listId = null;
            $taskId = null;
            continue;
        }

    } catch (TokenExpiredException $exception) {
        writeLog('Token expired');
        $token = null;
    } catch (Exception $exception) {
        writeLog('Exception: ' . $exception->getMessage());
    }
}