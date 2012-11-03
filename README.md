StatsD PHP client library
=========================

StatsD PHP5 client library for integrating [Etsy][etsy]'s [StatsD][statsd] into your application.

This implementaton is based on the provided PHP example in the original [StatsD][statsd] "examples" directory.

Usage
-----

If you want to use the component statically (which you should, you don't want to clutter your object graph with this dependency):

    // in some bootstap file...
    \StatsD\StatsD::setDefaultHandler(new \StatsD\UdpHandler($config->monitoring->host));
    \StatsD\StatsD::setDefaultNamespace($config->monitoring->namespace); (*)

(*) You should set a default namespace if you're planning to use the same StatsD daemon for different applications.
It's a good idea to mention the application name and environment in the namespace, for instance: "some-application.production".

Incrementing or decrementing a specific stat:

    // anywhere in your application
    \StatsD\StatsD::getInstance()->increment('stats.some-stat');
    \StatsD\StatsD::getInstance()->decrement('stats.some-stat');

Update a statistic with a specified value:

    \StatsD\StatsD::getInstance()->update('stats.some-stat', 2);

Update a statistic with an arbitrary value (gauge):

    \StatsD\StatsD::getInstance()->gauge('stats.some-stat', 100);

For statistics under heavy, really heavy load, use a sample rate:

    \StatsD\StatsD::getInstance()->increment('stats.some-busy-stat', 123, 0.1);


Note: You can also initialize the component via its constructor, but you'll have to inject it in every component you'll want to use it.

[etsy]: http://www.etsy.com
[statsd]: https://github.com/etsy/statsd

[![Build Status](https://secure.travis-ci.org/FabriZZio/statsd-php.png?branch=master)](http://travis-ci.org/FabriZZio/statsd-php)