<?php
require_once realpath(__DIR__.'/../../Metrix.php');

use \Metrix\Connection;

class MockBackend implements \Metrix\BackendInterface {
    protected $message;
    protected $options;
    protected $conn;

    public function __construct($options = null) {
        $this->options = $options;
    }

    public function setConnection(Connection $conn) {
        $this->conn = $conn;
    }

    public function getConnection() {
        return $this->conn;
    }

    public function increment($metrics) {
        $this->message = $metrics;
    }

    public function decrement($metrics) {
        $this->message = $metrics;
    }

    public function count($metrics) {
        $this->message = $metrics;
    }

    public function gauge($metrics) {
        $this->message = $metrics;
    }

    public function getLastMessage() {
        return $this->message;
    }

    public function getOptions() {
        return $this->options;
    }
}
