<?php
require_once realpath(__DIR__.'/Metrix/Version.php');
require_once realpath(__DIR__.'/Metrix/Exception.php');
require_once realpath(__DIR__.'/Metrix/ConnectionInterface.php');
require_once realpath(__DIR__.'/Metrix/BackendInterface.php');

use \Metrix\Exception;
use \Metrix\BackendInterface;

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
     * Delimiter used when attaching $prefix
     */
    protected $delimeter = ".";

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
        $options = (isset($config['opts']) ? $config['opts'] : array());
        $class = "Metrix\\Backend\\" . ucfirst($config['backend']);

        if (isset($config['prefix'])) {
            $this->prefix = $config['prefix'];
            $this->delimeter = ((isset($config['prefix_delimeter'])) ? $config['prefix_delimeter'] : ".");
        }

        if (class_exists($class)) {
            $this->backend = new $class($options);
        } else {
            throw new Exception("Backend `" . $config['backend'] . "` doesn't exist");
        }
    }

    /**
     * @param array|string $metrics
     * @param integer $delta
     */
    public function increment($metrics, $value = 1) {
        $normalized = $this->normalize($metrics);
        $prefixed = $this->prefixKeyNames($normalized);
        $this->backend->increment($prefixed);
    }

    /**
     * @param array|string $metrics
     * @param integer $delta
     */
    public function decrement($metrics, $value = 1) {
        $normalized = $this->normalize($metrics);
        $prefixed = $this->prefixKeyNames($normalized);
        $this->backend->decrement($prefixed);
    }

    /**
     * @param array|string $metric
     * @param integer $value
     */
    public function count($metrics, $value = null) {
        $normalized = $this->normalize($metrics, $value);
        $prefixed = $this->prefixKeyNames($normalized);
        $this->backend->count($prefixed);
    }

    /**
     * @param string $metric
     * @param integer $value
     */
    public function gauge($metrics, $value = null) {
        $normalized = $this->normalize($metrics, $value);
        $prefixed = $this->prefixKeyNames($normalized);
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
     * Set prefix for key
     *
     * @param string $prefix
     */
    public function setPrefix($prefix) {
        $this->prefix = $prefix;
    }

    /**
     * Accessor method for $prefix
     */
    public function getPrefix() {
        return $this->prefix;
    }

    ////
    // Private Methods

    /**
     * Normalize Parameters
     *
     * Changes
     *   array('key1', ...)
     *   array('key1' => 1, ...)
     *   'key1'
     *
     * To this
     *   array('key1' => 1, ...)
     */
    private function normalize($metrics, $value = 1) {
        if (is_string($metrics)) {
            return array($metrics => $value);
        }

        // Check if $metrics is an associative array
        if ( array_keys($metrics) !== range(0, count($metrics) - 1) ) {
            return $metrics;
        }

        $normalized = array();
        foreach ($metrics as $metric) {
            $normalized[$metric] = $value;
        }
        return $normalized;
    }

    /**
     * Prefixes keys in a hash with given $prefix
     */
    private function prefixKeyNames($metrics) {
        if ($this->prefix == null)
            return $metrics;

        $prefixed = array();
        foreach($metrics as $key => $value) {
            $prefixed[$this->prefix . $this->delimeter . $key] = $value;
        }

        return $prefixed;
    }
}
