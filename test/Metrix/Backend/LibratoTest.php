<?php
require_once realpath(__DIR__.'/../../../Metrix.php');

class LibratoTest extends PHPUnit_Framework_TestCase {
    protected $client;

    public function setUp() {
        $this->client = new Metrix;
    }

    public function tearDown() {
        unset($this->client);
    }
}
?>
