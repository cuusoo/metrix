<?php
require_once realpath(__DIR__.'/Metrix/Version.php');
require_once realpath(__DIR__.'/Metrix/Exception.php');
require_once realpath(__DIR__.'/Metrix/BackendInterface.php');

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
    public function __construct(array $conf = null) {
        if (isset($conf)) {
            $this->config($conf);
        }
    }

    /**
     * @param array $conf configuration hash:
     *   string backend the metrics service you want to communicate with
     *   array opts options to pass backend
     *   string prefix key prefix to attach to individual keys before reporting
     */
    public function config(array $config) {
        $options = $config['opts'];
        $class = "Metrix\\Backend\\" . ucfirst($config['backend']);

        if (isset($config['prefix']))
            $this->prefix = $config['prefix'];

        if (class_exists($class)) {
            $this->backend = new $class($options);
        } else {
            throw new Exception("Backend `" . $config['backend'] . "` doesn't exist");
        }
    }

    /**
     * @param array $metrics
     */
    public function count(array $metrics) {
        $prefixed = $this->prefixKeyNames($metrics, $this->prefix);
        $this->backend->count($prefixed);
    }

    /**
     * @param array $metrics
     */
    public function gauge(array $metrics) {
        $prefixed = $this->prefixKeyNames($metrics, $this->prefix);
        $this->backend->gauge($prefixed);
    }


    /**
     * Accessor method for $backend
     */
    public function getBackend() {
        return $this->backend;
    }

    /**
     * Setter method for $backend
     */
    public function setBackend(BackendInterface $backend) {
        $this->backend = $backend;
    }

    /**
     * Prefixes keys in a hash with given $prefix
     *
     * @param array $metrics hash containing metric key,value pairs
     * @param string $prefix string to prefix key names with
     */
    private function prefixKeyNames($metrics, $prefix) {
        if ($prefix == null)
            return $metrics;

        $prefixed = array();
        foreach($metrics as $key => $value) {
            $prefixed[$prefix . $key] = $value;
        }

        return $prefixed;
    }
}
