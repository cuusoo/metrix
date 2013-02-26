<?php
require_once realpath(__DIR__.'/../../../Metrix.php');

class LibratoTest extends PHPUnit_Framework_TestCase {
    protected $client;
    protected $mockHttpClient;

    public function setUp() {
        // Setup Librato Instance
        $this->client = new Metrix(array(
            'backend' => 'librato',
            'opts' => array(
                'email' => '123',
                'token' => '123'
            )
        ));
    }

    public function tearDown() {
        unset($this->client);
    }

    public function testReturnsErrorOnBadHTTPCode() {
        $mock = $this->getMock("\HTTP_Request2");
        $mock->expects($this->any())
             ->method('send')
             ->will($this->returnValue(''));
        $this->client->getBackend()->setHttpClient($mock);
    }

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
