<?php
require_once realpath(__DIR__.'/../../Metrix.php');

class MockConnection implements \Metrix\Connection {
    protected $message;

    public function send($message, $options = null) {
        $this->message = $message;
    }

    public function getLastMessage() {
        return $this->message;
    }
}
