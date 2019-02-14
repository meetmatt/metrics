<?php

namespace MeetMatt\Metrics\Client;

class Api extends Curl
{
    public function register(string $username, string $password): void
    {
        $this->post('/user', ['username' => $username, 'password' => $password]);
    }

    public function login(string $username, string $password): string
    {
        return $this->post('/login', ['username' => $username, 'password' => $password])->body['token'];
    }

    public function createList(string $token, string $name): string
    {
        return $this->post('/lists', ['name' => $name], $token)->body['id'];
    }

    public function getLists(string $token): array
    {
        return $this->get('/lists', $token)->body;
    }

    public function getList(string $token, string $id): array
    {
        return $this->get('/lists/' . $id, $token)->body;
    }

    public function getTasks(string $token, string $listId): array
    {
        return $this->get('/lists/' . $listId . '/tasks', $token)->body;
    }

    public function createTask(string $token, string $listId, string $summary): string
    {
        return $this->post('/lists/' . $listId . '/tasks', ['summary' => $summary], $token)->body['id'];
    }

    public function markTask(string $token, string $listId, string $taskId, bool $isDone): void
    {
        $this->patch('/lists/' . $listId . '/tasks/' . $taskId, ['is_done' => $isDone], $token);
    }

    public function deleteTask(string $token, string $listId, string $taskId): void
    {
        $this->delete('/lists/' . $listId . '/tasks/' . $taskId, $token);
    }

    public function deleteList(string $token, string $listId): void
    {
        $this->delete('/lists/' . $listId, $token);
    }
}