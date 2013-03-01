<?php
require_once realpath(__DIR__.'/../../Metrix.php');
require_once realpath(__DIR__.'/MockBackend.php');

class MetrixTest extends PHPUnit_Framework_TestCase {
    protected $client;
    protected $mockBackend;

    protected $conf = array(
        'backend' => 'librato',
        'prefix' => 'test',
        'opts' => array(
            'email' => '123',
            'token' => '123'
        ),

    );

    public function setUp() {
        $this->client = new Metrix;
        $this->mockBackend = new MockBackend();
        $this->client->setBackend($this->mockBackend);
    }

    public function tearDown() {
        unset($this->client);
    }

    public function testSetConfigThroughConstructor() {
        $c = new Metrix($this->conf);
        $this->assertTrue($c->getBackend() instanceOf \Metrix\Backend\Librato);
    }

    public function testSetConfig() {
        $this->client->config($this->conf);
        $this->assertTrue($this->client->getBackend() instanceOf \Metrix\Backend\Librato);
    }

    // Users should be able to pass no options and let
    // the backend decide how to default parameters
    public function testNoPassOptions() {
        // doesn't test anything right now, simply runs code right
        // now to test if its working
        $this->client->config(array( 'backend' => 'statsd' ));
    }

    public function testKeyPrefix() {
        $this->client->setPrefix('foo');
        $this->assertEquals('foo', $this->client->getPrefix());

        $this->client->increment('key');
        $expected = $this->client->getBackend()->getLastMessage();
        $this->assertEquals($expected, array( 'foo.key' => 1 ));
    }
}
