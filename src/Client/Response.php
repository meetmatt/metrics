<?php

namespace MeetMatt\Metrics\Client;

class Response
{
    const HEADERS_PATTERN = '#HTTP/\d\.\d.*?$.*?\r\n\r\n#ims';
    const VERSION_HEADER_PATTERN = '#HTTP/(\d\.\d)\s(\d\d\d)\s(.*)#';

    /** @var int */
    public $status;

    /** @var array */
    public $body;

    function __construct(string $response)
    {
        // extract headers from response
        preg_match_all(self::HEADERS_PATTERN, $response, $matches);
        $headersString = array_pop($matches[0]);
        $headers = explode("\r\n", str_replace("\r\n\r\n", '', $headersString));

        // remove headers from the response body
        $body = str_replace($headersString, '', $response);

        if (!empty($body)) {
            $this->body = json_decode($body, true);
        } else {
            $this->body = [];
        }

        // extract the version and status from the first header
        $version_and_status = array_shift($headers);
        preg_match(self::VERSION_HEADER_PATTERN, $version_and_status, $matches);
        $this->status = (int)$matches[2];
    }
}