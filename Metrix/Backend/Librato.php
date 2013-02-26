<?php
namespace Metrix\Backend;

require_once 'HTTP/Request2.php';

use \Metrix\Exception;
use \HTTP_Request2 as HTTPClient;
use \HTTP_Request2_Response as HttpResponse;
use \HTTP_Request2_Exception as HttpException;

class Librato implements \Metrix\BackendInterface {
    const API_ENDPOINT = 'https://metrics-api.librato.com/v1/metrics';

    /**
     * HTTP Client Object
     */
    protected $httpClient;

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

        $this->httpClient = new HTTPClient(self::API_ENDPOINT);
        $this->httpClient->setAuth($email, $token, HTTPClient::AUTH_BASIC);
        $this->httpClient->setHeader(array(
            'User-Agent' => \Metrix\Version::NAME . " version " .\Metrix\Version::NUMBER,
            'Content-Type' => 'application/json',
            'Connection' => 'keep-alive'
        ));
    }

    //
    // Start BackendInterface
    //

    public function increment($metrics, $delta = 1) {
        throw new \RuntimeException("Librato only supports absolute counters");
    }

    public function decrement($metrics, $delta = 1) {
        throw new \RuntimeException("Librato only supports absolute counters");
    }

    public function count($metrics) {
        $json = $this->prepareJSON('counters', $metrics);
        return $this->post($json);
    }

    public function gauge($metrics) {
        $json = $this->prepareJSON('gauges', $metrics);
        return $this->post($json);
    }

    //
    // End BackendInterface
    //

    public function setHttpClient($httpClient) {
        $this->httpClient = $httpClient;
    }

    private function post($body) {
        try {
            $this->httpClient->setMethod(HTTPClient::METHOD_POST);
            $this->httpClient->setBody($body);
            $response = $this->httpClient->send();
        } catch (HttpException $e) {
            throw new Exception("HTTP error: " . $e->getMessage());
        }

        return $response->getStatus() == 200;
    }


    private function prepareJSON($type, $metrics) {
        $data = array();

        foreach($metrics as $key => $value) {
            $data[$key] = array('value' => $value);
        }

        $toEncode = array($type => $data);
        return json_encode($toEncode);
    }
}
