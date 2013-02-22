<?php
namespace Metrix;

interface Backend {
    /**
     * <code>
     *   $metrics = array(
     *     'key' => 'value'
     *   );
     * </code>
     *
     * @param array $metrics
     */
    public function count(array $metrics);

    /**
     * <code>
     *   $metrics = array(
     *     'key' => 'value'
     *   );
     * </code>
     *
     * @param array $metrics
     */
    public function gauge(array $metrics);
}

require_once realpath(__DIR__.'/Backend/Librato.php');
?>
