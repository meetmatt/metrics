<?php

namespace MeetMatt\Metrics\Client;

use Exception;

class Curl
{
    /** @var string */
    protected $baseUrl;

    public function __construct(string $baseUrl)
    {
        $this->baseUrl = $baseUrl;
    }

    protected function get($url, $token = null): Response
    {
        return $this->request('GET', $url, null, $token);
    }

    protected function post($url, array $data, $token = null): Response
    {
        return $this->request('POST', $url, $data, $token);
    }

    protected function patch($url, array $data, $token): Response
    {
        return $this->request('PATCH', $url, $data, $token);
    }

    protected function delete($url, $token): Response
    {
        return $this->request('DELETE', $url, null, $token);
    }

    /**
     * @param string $method
     * @param string $url
     * @param array  $data
     * @param string $token
     *
     * @throws TokenExpiredException
     * @throws Exception
     *
     * @return Response
     */
    private function request($method, $url, array $data = null, $token = null): Response
    {
        $request = curl_init();

        switch ($method) {
            case 'GET':
                curl_setopt($request, CURLOPT_HTTPGET, true);
                break;
            case 'POST':
                curl_setopt($request, CURLOPT_POST, true);
                break;
            default:
                curl_setopt($request, CURLOPT_CUSTOMREQUEST, $method);
        }

        curl_setopt($request, CURLOPT_URL, $this->baseUrl . $url);

        if (!empty($data)) {
            curl_setopt($request, CURLOPT_POSTFIELDS, json_encode($data));
        }
        if ($token) {
            curl_setopt($request, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $token]);
        }
        curl_setopt($request, CURLOPT_HEADER, true);
        curl_setopt($request, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($request);

        if ($response !== false) {
            $response = new Response($response);
            if ($response->status >= 500) {
                $exception = new Exception('Server error: ' . $response->status);
            } elseif ($response->status >= 400) {
                switch ($response->status) {
                    case 400:
                        $exception = new Exception('Bad request');
                        break;
                    case 401:
                        $exception = new TokenExpiredException('Token expired');
                        break;
                    case 403:
                        $exception = new Exception('Forbidden');
                        break;
                    case 404:
                        $exception = new Exception('Not found');
                        break;
                    default:
                        $exception = new Exception('Unknown status: ' . $response->status);
                }
            }
        } else {
            $exception = new Exception(curl_errno($request) . ' - ' . curl_error($request));
        }

        curl_close($request);

        if (isset($exception)) {
            throw $exception;
        }

        return $response;
    }
}