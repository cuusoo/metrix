<?php
namespace Metrix\Backend;

use \Metrix\Exception;
use \Metrix\Connection;

class Librato implements \Metrix\BackendInterface {
    const API_ENDPOINT = 'https://metrics-api.librato.com/v1/metrics';

    /**
     * Connection Object
     */
    protected $conn;

    /**
     * @param array $options hash of standard options for librato backend:
     *  string email the email address of your Librato account
     *  string token API key for your Librato account
     */
    public function __construct(array $options) {
        if (!isset($options['email'])) {
            throw new \InvalidArgumentException("Librato requires `email` parameter");
        }
        if (!isset($options['token'])) {
            throw new \InvalidArgumentException("Librato requires `token` parameter");
        }

        $email= $options['email'];
        $token = $options['token'];

        $this->conn = new \Metrix\Connection\HTTPClient(self::API_ENDPOINT, $email, $token);
    }

    ////
    // Accessors / Setters

    public function setConnection(Connection $conn) {
        $this->conn = $conn;
    }

    public function getConnection() {
        return $this->conn;
    }

    ////
    // BackendInterface

    public function increment($metrics) {
        throw new \RuntimeException("Librato only supports absolute counters");
    }

    public function decrement($metrics) {
        throw new \RuntimeException("Librato only supports absolute counters");
    }

    public function count($metrics) {
        $json = $this->prepareJSON('counters', $metrics);
        return $this->conn->send($json);
    }

    public function gauge($metrics) {
        $json = $this->prepareJSON('gauges', $metrics);
        return $this->conn->send($json);
    }

    ////
    // Private Methods

    private function prepareJSON($type, $metrics) {
        $data = array();

        foreach($metrics as $key => $value) {
            $data[$key] = array('value' => $value);
        }

        $toEncode = array($type => $data);
        return json_encode($toEncode);
    }
}
