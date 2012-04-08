StatsD PHP client library
=========================

StatsD PHP5 client library for integrating [Etsy][etsy]'s [StatsD][statsd] into your application.

This implementaton is based on the provided PHP example in the original [StatsD][statsd] "examples" directory.

Usage
-----

Initializing the statsD client is simple:

    use StatsD\StatsD;
    use StatsD\NodeJsHandler;

    $statsD = new StatsD(new NodeJsHandler('localhost'));

Incrementing or decrementing a specific stat: 

    $statsD->increment('stats.some-stat');
    $statsD->decrement('stats.some-stat');

Update a statistic with a specified value:

    $statsD->update('stats.some-stat', 2);

For statistics under heavy, really heavy load, use a sample rate:

    $statsD->increment('stats.some-busy-stat', 123, 0.1);

[etsy]: http://www.etsy.com
[statsd]: https://github.com/etsy/statsd

[![Build Status](https://secure.travis-ci.org/FabriZZio/statsd-php.png?branch=master)](http://travis-ci.org/FabriZZio/statsd-php)