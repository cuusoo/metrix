<?php
namespace Metrix;

interface BackendInterface {
    /**
     * <code>
     *   $metrics = array('key1', 'key2', 'key3')
     *   $metrics = 'key1'
     * </code>
     *
     * @param string|array $metrics
     * @param integer $delta how much you want to increment the counter by
     *        default: 1
     */
    public function increment($metrics, $delta);

    /**
     * <code>
     *   $metrics = array('key1', 'key2', 'key3')
     *   $metrics = 'key1'
     * </code>
     *
     * @param string|array $metrics
     * @param integer $delta how much you want to decrement the counter by
     *        default: 1
     */
    public function decrement($metrics, $delta);

    /**
     * <code>
     *   $metric = 'key1'
     * </code>
     *
     * @param string $metrics
     */
    public function gauge($metric, $value);
}

require_once realpath(__DIR__.'/Backend/Librato.php');
