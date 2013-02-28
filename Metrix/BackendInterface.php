<?php
namespace Metrix;

interface BackendInterface {
    /**
     * Increment counter by a value
     *
     * <code>
     *   $metrics = array('key1', 'key2', 'key3')
     *   $metrics = array(
     *      'key' => 1
     *   )
     *   $metrics = 'key1'
     * </code>
     *
     * @param string|array $metrics
     * @param integer $delta how much you want to increment the counter by
     *        defaults to 1
     */
    public function increment($metrics);

    /**
     * Decrement counter by a value
     *
     * <code>
     *   $metrics = array('key1', 'key2', 'key3')
     *   $metrics = array(
     *      'key' => 1
     *   )
     *   $metrics = 'key1'
     * </code>
     *
     * @param string|array $metrics
     * @param integer $delta how much you want to decrement the counter by
     *        defaults to 1
     */
    public function decrement($metrics);

    /**
     * Send absolute counter value
     *
     * <code>
     *   $metrics = array('key1', 'key2', 'key3')
     *   $metrics = array(
     *      'key' => 1
     *   )
     *   $metrics = 'key1'
     * </code>
     *
     * @param string $metrics
     * @param integer $value
     */
    public function count($metrics);

    /**
     * Send a gauge value
     *
     * <code>
     *   $metrics = array('key1', 'key2', 'key3')
     *   $metrics = array(
     *      'key' => 1
     *   )
     *   $metrics = 'key1'
     * </code>
     *
     * @param string $metrics
     * @param integer $value
     */
    public function gauge($metrics);

    public function setConnection(Connection $conn);

    public function getConnection();
}

require_once realpath(__DIR__.'/Backend/Librato.php');
require_once realpath(__DIR__.'/Backend/StatsD.php');
