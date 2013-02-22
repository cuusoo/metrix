<?php
namespace Metrix\Backend;

require_once 'HTTP/Request2.php';

use \Metrix\Exception;
use \HTTP_Request2 as HTTPClient;
use \HTTP_Request2_Response as HttpResponse;
use \HTTP_Request2_Exception as HttpException;

class Librato implements \Metrix\Backend {
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
            throw new Exception("Librato requires `email` parameter");
        }
        if (!isset($options['token'])) {
            throw new Exception("Librato requires `token` parameter");
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

    /**
     * See documentation in interface class
     */
    public function gauge(array $metrics) {
        $json = $this->prepareJSON('gauges', $metrics);
        return $this->post($json);
    }

    /**
     * See documentation in interface class
     */
    public function count(array $metrics) {
        $json = $this->prepareJSON('counters', $metrics);
        return $this->post($json);
    }

    private function post($body) {
        try {
            $this->httpClient->setMethod(HTTPClient::METHOD_POST);
            $this->httpClient->setBody($body);
            $response = $this->httpClient->send();
        } catch (HttpException $e) {
            throw new Exception("HTTP error: " . $e->getMessage());
        }

        echo $response->getBody();
        return $response->getStatus() == 200;
    }

    protected function prepareJSON($type, array $metricsOld) {
        $metrics = array();

        foreach($metricsOld as $key => $value) {
            $metrics[$key] = array('value' => $value);
        }

        $toEncode = array($type => $metrics);
        return json_encode($toEncode);
    }
}
?>
