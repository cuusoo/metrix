<?php
namespace Metrix\Backend;

use \Metrix\Connection;

class StatsD implements \Metrix\BackendInterface {
    protected $options;
    protected $conn;

    public function __construct($options = array()) {
        if (!isset($options['port']) || empty($options['port'])) {
            $options['port'] = 8125;
        }
        if (!isset($options['host']) || empty($options['host'])) {
            $options['host'] = '127.0.0.1';
        }
        $this->options = $options;
        $this->conn = new \Metrix\Connection\UDPSocket($options['host'], $options['port']);
    }

    ////
    // Accessor/Setters

    public function setConnection(Connection $conn) {
        $this->conn = $conn;
    }

    public function getConnection() {
        return $this->conn;
    }

    ////
    // BackendInterface
    //

    public function increment($metrics) {
        $packet = $this->buildPacket($metrics, 'c');
        $this->send($packet);
    }

    public function decrement($metrics) {
        $map = array();
        foreach ($metrics as $key => $value) {
            $map[$key] = -1*$value;
        }
        $packet = $this->buildPacket($map, 'c');
        $this->send($packet);
    }

    public function count($metrics) {
        $packet = $this->buildPacket($metrics, 'c');
        $this->send($packet);
    }

    public function gauge($metrics) {
        $packet = $this->buildPacket($metrics, 'g');
        $this->send($packet);
    }

    ////
    // Private Methods

    private function buildPacket($metrics, $type) {
        // StatsD allows you to concatenate operations
        // eg. gorets:1|c\nbucket:1|c
        $packet = '';

        foreach ($metrics as $key => $amount) {
            if ($packet != '') {
                $packet .= "\n";
            }
            $packet .= "$key:$amount|$type";
        }

        return $packet;
    }

    private function send($packet) {
        return $this->conn->send($packet);
    }
}
