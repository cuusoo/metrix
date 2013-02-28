<?php
namespace Metrix\Connection;

require_once 'HTTP/Request2.php';

use \HTTP_Request2_Response as HttpResponse;
use \HTTP_Request2_Exception as HttpException;

class HTTPClient implements \Metrix\Connection {
    protected $httpClient;

    public function __construct($endpoint, $email, $token) {
        $this->httpClient = new \HTTP_Request2($endpoint);
        $this->httpClient->setAuth($email, $token, \HTTP_Request2::AUTH_BASIC);
        $this->httpClient->setHeader(array(
            'User-Agent' => \Metrix\Version::NAME . " version " .\Metrix\Version::NUMBER,
            'Content-Type' => 'application/json',
            'Connection' => 'keep-alive'
        ));
    }

    public function send($message, $options = null) {
        $this->httpClient->setMethod(\HTTPClient::METHOD_POST);
        $this->httpClient->setBody($message);

        try {
            $this->httpClient->setMethod(\HTTP_Request2::METHOD_POST);
            $this->httpClient->setBody($body);
            $response = $this->httpClient->send();
        } catch (HttpException $e) {
            throw new Exception("HTTP error: " . $e->getMessage());
        }

        return true;
    }
}
