<?php
require_once realpath(__DIR__.'/../../../Metrix.php');
require_once 'HTTP/Request2/Response.php';

use \HTTP_Request2_Response as HttpResponse;

class MockHTTPClient {
    public $body;
    public $sent;
    public $header;
    public $response;

    public function setAuth($a, $b, $c) { }
    public function setHeader($arr) { }
    public function setMethod($method) { }
    public function setBody($body) {
        $this->body = $body;
    }
    public function send() {
        return $this->response;
    }
}

class LibratoTest extends PHPUnit_Framework_TestCase {
    protected $client;
    protected $mockHttpClient;

    //
    // TEST SETUP
    //

    public function setUp() {
        ////
        // Setup Librato Instance
        $this->client = new Metrix(array(
            'backend' => 'librato',
            'opts' => array(
                'email' => '123',
                'token' => '123'
            )
        ));

        ////
        // Mock out HTTPClient
        $mocked = new MockHTTPClient();
        $mocked->response = new HttpResponse("HTTP/1.1 200 OK");
        $this->mockHttpClient = $mocked;
        $this->client->getBackend()->setHttpClient($this->mockHttpClient);
    }

    //
    // TEST CLEANUP
    //

    public function tearDown() {
        unset($this->client);
        unset($this->mockHttpClient);
    }

    //
    // SIMPLE INTERFACE TESTS
    //

    public function testIncrement() {
        $expected = "Librato only supports absolute counters";
        try {
            $this->client->increment('key');
        } catch (Exception $e) {
            $this->assertEquals($expected, $e->getMessage());
        }
    }

    public function testDecrement() {
        $expected = "Librato only supports absolute counters";
        try {
            $this->client->decrement('key');
        } catch (Exception $e) {
            $this->assertEquals($expected, $e->getMessage());
        }
    }

    public function testCount() {
        $expected = "{\"counters\":{\"key\":{\"value\":1000}}}";
        $this->client->count('key', 1000);
        $this->assertEquals($expected, $this->mockHttpClient->body);
    }

    public function testGauge() {
        $expected = "{\"gauges\":{\"key\":{\"value\":10}}}";
        $this->client->gauge('key', 10);
        $this->assertEquals($expected, $this->mockHttpClient->body);
    }

    //
    // TEST EXCEPTIONAL ERRORS
    //

    public function testMissingEmailParam() {
        $expected = 'Librato requires `email` parameter';

        try {
            $this->client->config(array(
                'backend' => 'librato',
                'opts' => array( 'token' => '123' )
            ));
        } catch (InvalidArgumentException $e) {
            $actual = $e->getMessage();
            $this->assertEquals($expected, $actual);
        }
    }

    public function testMissingTokenParam() {
        $expected = 'Librato requires `token` parameter';

        try {
            $this->client->config(array(
                'backend' => 'librato',
                'opts' => array( 'email' => '123' )
            ));
        } catch (InvalidArgumentException $e) {
            $actual = $e->getMessage();
            $this->assertEquals($expected, $actual);
        }
    }
}
