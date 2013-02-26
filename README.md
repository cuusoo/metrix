Metrix PHP Library
==================

Metrix is a PHP library that provides a simple interface for sending metric
data to a number of services.

Configuration
=============

Librato
-------

    $client = new Metrix();
    $client->config(array(
        'backend' => 'librato',
        'opts' => array(
          'email' => 'test@user.com',
          'token' => '123'
          )
    ));

StatsD
------

    $client = new Metrix();
    $client->config(array(
        'backend' => 'statsd',
        'opts' => array(
          'host' => '1.1.1.1',
          'port' => 8125
        )
    ))

Sending Metrics
===============

    require 'Metrix.php';
    use Metrix;

    $client = new Metrix();
    $client->config(...);

    $client->count(
        'key1-counter' => 1,
        'key2-counter' => 1,
        ...
    );

    $client->gauge(
        'key1-gauge' => 1.2,
        'key2-gauge' => 5.0
        ...
    );

TODO
====

* Specify `source` for Metrics
