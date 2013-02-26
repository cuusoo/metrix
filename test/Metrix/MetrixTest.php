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
        new Metrix($this->conf);
    }

    public function testSetConfig() {
        $this->client->config($this->conf);
    }
}
