<?php

namespace Metrix;
/**
 * An interface for sending
 */
interface Connection {
    /**
     * Sends a message to backend
     */
    public function send($message, $options);
}

require_once realpath(__DIR__.'/Connection/HTTPClient.php');
require_once realpath(__DIR__.'/Connection/UDPSocket.php');
