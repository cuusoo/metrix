<?php
require_once realpath(__DIR__.'/../../Metrix.php');

class MetrixTest extends PHPUnit_Framework_TestCase {
    protected $client;
    protected $conf = array(
        'backend' => 'librato',
        'opts' => array(
            'email' => '123',
            'token' => '123'
        )
    );

    public function setUp() {
        $this->client = new Metrix;
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

    public function testSetPrefix() {
        $c = new Metrix($this->conf);
        $c->setPrefix('foo');
        $this->assertEquals('foo', $c->getPrefix());
    }
}
