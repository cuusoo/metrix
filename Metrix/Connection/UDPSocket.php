<?php
namespace Metrix\Connection;

class UDPSocket implements \Metrix\Connection {
    protected $host, $port;

    public function __construct($host, $port) {
        $this->host = $host;
        $this->port = $port;
    }

    public function send($message, $options = null) {
        $fp = fsockopen("udp://$this->host", $this->port, $errno, $errstr);
        fwrite($fp, $packet);
        fclose($fp);

        return true;
    }
}
