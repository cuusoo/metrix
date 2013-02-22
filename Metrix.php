<?php
require_once realpath(__DIR__.'/Metrix/Version.php');
require_once realpath(__DIR__.'/Metrix/Exception.php');
require_once realpath(__DIR__.'/Metrix/Backend.php');

use \Metrix\Exception;

class Metrix {
    /**
     * Instance of metrics backend class
     */
    protected $backend;

    /**
     * Prefix to add to key names
     */
    protected $prefix;

    /**
     * @param array $conf configuration hash:
     *   string backend the metrics service you want to communicate with
     *   array opts options to pass backend
     *   string prefix key prefix to attach to individual keys before reporting
     */
    public function setConfig($conf) {
        $class = $conf['backend'];
        $options = $conf['opts'];
        $klass = "Metrix\\Backend\\" . ucfirst($class);
        $this->prefix = $conf['prefix'];

        if (class_exists($klass)) {
            $this->backend = new $klass($options);
        } else {
            throw new Exception("Backend `" . $klass . "` doesn't exist");
        }
    }

    /**
     * @param array $metrics
     */
    public function count(array $metrics) {
        $metrics = $this->prefixKeyNames($metrics, $this->prefix);
        $this->backend->count($metrics);
    }

    /**
     * @param array $metrics
     */
    public function gauge(array $metrics) {
        $metrics = $this->prefixKeyNames($metrics, $this->prefix);
        $this->backend->gauge($metrics);
    }

    /**
     * Prefixes keys in a hash with given $prefix
     *
     * @param array $metricsOld hash containing metric key,value pairs
     * @param string $prefix string to prefix key names with
     */
    private function prefixKeyNames($metricsOld, $prefix) {
        if ($prefix == null) return $metricsOld;

        $metrics = array();
        foreach($metricsOld as $key => $value) {
            $metrics[$prefix . $key] = $value;
        }

        return $metrics;
    }
}
?>
