<?php
namespace Metrix\Backend;

class StatsD implements \Metrix\BackendInterface {
    protected $options;

    public function __construct(array $options) {
        if (!isset($options['port']) || empty($options['port'])) {
            $options['port'] = 8125;
        }
        if (!isset($options['host']) || empty($options['host'])) {
            $options['host'] = '127.0.0.1';
        }
        $this->options = $options;
    }

    ////
    // BackendInterface
    //

    public function increment($metrics) {
    }

    public function decrement($metrics) {
    }

    public function count($metric, $value) {
    }

    public function gauge($metric, $value) {
    }

    ////
    // Private Methods

    private function update() {
    }

    private function send(array $data) {
        try {
            $host = $this->options['host'];
            $port = $this->optoins['port'];

            $fp = fsockopen("udp://$host", $port, $errno, $errstr);

            if (!$fp) { return false; }

            foreach ($data as $bucket => $value) {
                fwrite($fp, "$bucket:$value");
            }
            fclose($fp);
        } catch (Exception $e) {
            return false;
        }
        return true;
    }


}
