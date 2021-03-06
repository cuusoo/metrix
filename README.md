Metrix PHP Library
==================

Metrix is a PHP library that provides a simple interface for sending metric
data to a number of services. Currently Metrix supports StatsD and Librato.

[![Build Status](https://secure.travis-ci.org/cuusoo/metrix.png?branch=master)](http://travis-ci.org/cuusoo/metrix)

Configuration
=============

Librato:

    $client = new Metrix();
    $client->config(array(
        'backend' => 'librato',
        'opts' => array(
          'email' => 'test@user.com',
          'token' => '123'
        )
    ));

StatsD:

    $client = new Metrix();
    $client->config(array(
        'backend' => 'statsd',
        'opts' => array(
          'host' => '127.0.0.1', // defaults to this
          'port' => '8125' // defaults to this
        )
    ));

Counters
========

    $client->increment('key', 10);
    $client->increment(array('key1', 'key2', ...));
    $client->increment(array(
      'key' => 1
    ));

    $client->decrement('key', 10);
    ...

Gauges
======

    $client->gauge('cputemp', 55);

Other Options
=============

Custom key prefixes (prefix\_delimeter defaults to '.'):

    $client->config(array('
      'prefix' => 'foo',
      'prefix_delimeter' => ':',
      ...
    ));

TODO
====

* Specify `source` for Metrics
