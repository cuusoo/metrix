<?php
require_once realpath(__DIR__.'/../../../Metrix.php');

class LibratoTest extends PHPUnit_Framework_TestCase {
    protected $client;

    public function setUp() {
        $this->client = new Metrix;
        $this->client->config(array(
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
?>
