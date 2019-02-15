<?php

namespace MeetMatt\Metrics\Server\Infrastructure\Metrics;

use DataDog\DogStatsd;

class RemoteUdpBatchedDogStatsd extends DogStatsd
{
    /** @var string */
    private $_host;

    /** @var int */
    private $_port;

    private $buffer = [];
    private $bufferLength = 0;
    private $maxBufferLength = 50;

    public function __construct(array $config)
    {
        $this->_host = $config['host'];
        $this->_port = $config['port'];

        parent::__construct($config);
    }

    public function report($udp_message): void
    {
        $this->buffer[] = $udp_message;
        $this->bufferLength++;
        if ($this->bufferLength > $this->maxBufferLength) {
            $this->flushBuffer();
        }
    }

    public function flushBuffer(): void
    {
        $this->flush(implode("\n", $this->buffer));
        $this->buffer       = [];
        $this->bufferLength = 0;
    }

    public function flush($udp_message)
    {
        $socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
        socket_set_nonblock($socket);
        @socket_sendto($socket, $udp_message, strlen($udp_message), 0, $this->_host, $this->_port);
        socket_close($socket);
    }
}