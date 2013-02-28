<?php
require_once realpath(__DIR__.'/../../../Metrix.php');

class StatsDTest extends PHPUnit_Framework_TestCase {
    protected $client;
    protected $mockConnection;

    public function setUp() {
        ////
        // Setup StatsD Instance
        $this->client = new Metrix(array(
            'backend' => 'statsd',
            'opts' => array(
                'host' => 'localhost',
                'token' => '123'
            )
        ));

        ////
        // Mock out UDP socket
        $conn = new MockConnection();
        $this->mockConnection = $conn;
        $this->client->getBackend()->setConnection($conn);
    }

    //
    // TEST CLEANUP
    //

    public function tearDown() {
        unset($this->client);
        unset($this->mockConnection);
    }

    public function testIncrement() {
        $expected = "key1:1|c\nkey2:1|c";
        $this->client->increment(array('key1', 'key2'));
        $this->assertEquals($expected, $this->mockConnection->getLastMessage());
    }

    public function testDecrement() {
        $expected = "key1:-1|c\nkey2:-1|c";
        $this->client->decrement(array('key1', 'key2'));
        $this->assertEquals($expected, $this->mockConnection->getLastMessage());
    }

    public function testCount() {
        $expected = "key:20|c";
        $this->client->count('key', 20);
        $this->assertEquals($expected, $this->mockConnection->getLastMessage());
    }

    public function testGuage() {
        $expected = "key:20|g";
        $this->client->gauge('key', 20);
        $this->assertEquals($expected, $this->mockConnection->getLastMessage());
    }

}
